<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\DeviceType;
use App\Models\Brand;

class DeviceCompatibility extends Component
{
    public $deviceTypes;
    public $brands;

    public function __construct()
    {
        $this->deviceTypes = DeviceType::with(['brands.models.exceptions'])
                                    ->whereHas('brands.models')
                                    ->get();

        $this->brands = Brand::with('deviceType')
                           ->whereHas('models')
                           ->get()
                           ->unique('name');
    }

    public function render()
    {
        return view('components.device-compatibility-modal');
    }
}