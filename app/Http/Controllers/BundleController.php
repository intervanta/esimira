<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bundle;
use App\Models\Refill;
use App\Models\DeviceType;
use App\Models\Network;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class BundleController extends Controller
{
    public function show($slug)
    {
        // Get the bundle with active refills
        $bundle = Bundle::active()
            ->with(['refills' => function ($query) {
                $query->active()->orderBy('price');
            }])
            ->where('slug', $slug)
            ->firstOrFail();

        // Get bundle type for breadcrumb
        $bundleType = $this->getBundleType($bundle);

        // Get related bundles for the same country/region
        $relatedBundles = $this->getRelatedBundles($bundle);

        $deviceTypes = $this->getDeviceTypes($bundle);

        $extendedCoverageBundles = $this->getExtendedCoverageBundles($bundle);
        $networks = $this->getNetworkData($bundle);

        return view('pages.plandetails', [
            'bundle' => $bundle,
            'bundleType' => $bundleType,
            'relatedBundles' => $relatedBundles,
            'deviceTypes' => $deviceTypes,
            'extendedCoverageBundles' => $extendedCoverageBundles,
            'networks' => $networks,
        ]);
    }
    private function getNetworkData($bundle)
    {
        return Cache::remember("bundle_network_{$bundle->id}", now()->addHours(24), function () use ($bundle) {
            return Network::where('bundle_id', $bundle->id)
                ->active()
                ->select('title', 'local_networks', 'image')
                ->get()
                ->map(function ($network) {
                    // Ensure local_networks is properly formatted
                    if (is_string($network->local_networks)) {
                        try {
                            $network->local_networks = json_decode($network->local_networks, true);
                        } catch (\Exception $e) {
                            // If not valid JSON, treat as comma-separated
                            $network->local_networks = array_map('trim', explode(',', $network->local_networks));
                        }
                    }
                    return $network;
                });
        });
    }
    private function getDeviceTypes($bundle)
    {
        // Get device types for compatibility popup
        return Cache::remember('device.compatibility.popup', now()->addHours(24), function () {
            return DeviceType::with(['brands' => function ($q) {
                $q->whereHas('models')
                    ->with(['models' => function ($query) {
                        $query->orderBy('name');
                    }])
                    ->orderBy('name');
            }])
                ->whereHas('brands.models')
                ->orderBy('name', 'desc')
                ->get();
        });
    }


    private function getBundleType($bundle)
    {
        if ($bundle->is_country) return 'Local eSIMs';
        if ($bundle->is_region) return 'Regional eSIMs';
        if ($bundle->is_global) return 'Global eSIMs';
        if ($bundle->is_gcc) return 'GCC eSIMs';
        if ($bundle->is_lifetime) return 'Lifetime';

        return 'eSIMs';
    }

    private function getRelatedBundles($currentBundle)
    {
        return Bundle::active()
            ->with(['refills' => function ($query) {
                $query->active()->orderBy('price');
            }])
            ->where('id', '!=', $currentBundle->id)
            ->where(function ($query) use ($currentBundle) {
                if ($currentBundle->is_country) {
                    $query->where('is_country', true);
                } elseif ($currentBundle->is_region) {
                    $query->where('is_region', true);
                } elseif ($currentBundle->is_global) {
                    $query->where('is_global', true);
                } elseif ($currentBundle->is_gcc) {
                    $query->where('is_gcc', true);
                } elseif ($currentBundle->is_lifetime) {
                    $query->where('is_lifetime', true);
                }
            })
            ->where('is_popular', true)
            ->limit(4)
            ->get()
            ->map(function ($bundle) {
                return [
                    'id' => $bundle->id,
                    'name' => $bundle->name,
                    'slug' => $bundle->slug,
                    'image' => $bundle->image ? asset('storage/' . $bundle->image) : '/assets/images/default-country.svg',
                    'min_price' => $bundle->cheapest_price,
                    'currency_sign' => $bundle->currency_sign,
                ];
            });
    }
    private function getExtendedCoverageBundles($currentBundle)
    {
        return Bundle::active()
            ->with(['refills' => function ($query) {
                $query->active()->orderBy('price');
            }])
            ->where('id', '!=', $currentBundle->id)
            ->where(function ($query) use ($currentBundle) {
                // Only include regional or global bundles (not country-specific)
                $query->where('is_region', true)
                    ->orWhere('is_global', true)
                    ->orWhere('is_gcc', true)
                    ->orWhere('is_lifetime', true);
            })
            ->where(function ($query) use ($currentBundle) {
                // Check if this bundle's country is in the coverage list
                $query->whereJsonContains('coverage_list', $currentBundle->name)
                    ->orWhereJsonContains('coverage_list', strtolower($currentBundle->name));
            })
            ->get()
            ->map(function ($bundle) {
                $cheapestRefill = $bundle->refills->sortBy('price')->first();

                return [
                    'id' => $bundle->id,
                    'name' => $bundle->name,
                    'slug' => $bundle->slug,
                    'image' => $bundle->image ? asset('storage/' . $bundle->image) : '/assets/images/map.svg',
                    'min_price' => $cheapestRefill ? $cheapestRefill->sale_price : $bundle->cheapest_price,
                    'currency_sign' => $bundle->currency_sign,
                    'is_lifetime' => $bundle->currency_sign,
                    'is_gcc' => $bundle->currency_sign,
                    'is_global' => $bundle->currency_sign,
                    'is_region' => $bundle->currency_sign,
                    'type' => $bundle->is_region ? 'Regional' : ($bundle->is_global ? 'Global' : 'GCC'),
                ];
            });
    }
}
