<?php

namespace App\Http\Controllers;

use App\Http\Requests\Country\StoreCountryRequest;
use App\Models\Country;
use App\Services\CountryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CountryController extends Controller
{
    protected $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Country::class);
        return $request->fetch ? $this->countryService->fetch($request) : Inertia::render('Settings/Country/Index', $this->countryService->index($request));
        // return Inertia::render('Settings/Country/Index', $this->countryService->index($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Country::class);
        $props = [
            'title' => 'Create Country',
            'token' => csrf_token(),
        ];
        return Inertia::render('Settings/Country/Create', $props);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCountryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {
        $this->authorize('create', Country::class);
        $this->countryService->store($request->validated());
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        $this->authorize('view', Country::class);
        $props = [
            'title' => 'Country Details: #' . $country->id,
            'country' => $country,
        ];
        return Inertia::render('Settings/Country/Show', $props);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        $this->authorize('update', Country::class);
        $props = [
            'title' => "Update Country : #" . $country->id,
            'country' => $country,
            'token' => csrf_token(),
        ];
        return Inertia::render('Settings/Country/Edit', $props);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCountryRequest $request, Country $country)
    {
        $this->authorize('update', Country::class);
        $this->countryService->update($country, $request->validated());
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
