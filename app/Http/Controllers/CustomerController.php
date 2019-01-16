<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Http\Resources\CustomerResource;
use App\Helpers\ImageProcessing;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable()
    {
        $sortBy = \Request::get('sortBy');
        $rowsPerPage = \Request::get('rowsPerPage');
        $search = \Request::get('search');

        switch (\Request::get('descending')) 
        {
            case 'null':
                $sortType = null;
                break;
            case 'true':
                $sortType = 'desc';
                break;
            case 'false':
                $sortType = 'asc';
                break;
        }
        // Get Brands   
        $query = Customer::where(function ($query) use($search) {
                            $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('address', 'like', '%' . $search . '%');
                        });

        if (isset($sortType)) 
        {
            $query = $query->orderBy($sortBy, $sortType);
        }
        $brands = $query->paginate($rowsPerPage);

        // Return collection of Brands as a resource
        return CustomerResource::collection($brands);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = $request->isMethod('put') ? Customer::findOrFail($request->id) : new Customer;

        $customer->name= $request->input('name');
        $customer->email= $request->input('email');
        $customer->age= $request->input('age');
        $customer->address= $request->input('address');
        $customer->photo= $request->input('photo')? ImageProcessing::saveBase64ImagePng($request->input('photo')) : NULL;
        $customer->active= $request->input('active');
        $customer->created_by= $request->input('created_by');
        $customer->updated_by= $request->input('updated_by');
        
        if($customer->save()) 
        {
            return new CustomerResource($customer);
        }
    }
}
