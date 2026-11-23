<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @api {get} /api/cities Települések listázása
     * @apiName GetCities
     * @apiGroup City
     * @apiDescription Keresés és megye szerinti szűrés. A paraméterek kombinálhatók.
     *
     * @apiQuery {String} [search] Keresés a település nevében vagy irányítószámában
     * @apiQuery {Number} [county_id] Szűrés megye azonosító szerint
     * @apiQuery {String} [county] Szűrés megye neve szerint
     *
     * @apiSuccess {Object[]} cities Települések listája
     * @apiSuccess {Number} cities.id Azonosító
     * @apiSuccess {Number} cities.county_id A megye azonosítója
     * @apiSuccess {String} cities.name Település neve
     * @apiSuccess {String} cities.zip_code Irányítószám
     * @apiSuccess {Object} cities.county A megye (id, name)
     */
    public function index(Request $request)
    {
        $query = City::with('county');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('zip_code', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('county_id')) {
            $query->where('county_id', $request->county_id);
        }

        if ($request->filled('county')) {
            $query->whereHas('county', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->county . '%');
            });
        }

        return response()->json(['cities' => $query->get()]);
    }

    /**
     * @api {post} /api/cities Új település felvétele
     * @apiName CreateCity
     * @apiGroup City
     * @apiHeader {String} Authorization Bearer token
     *
     * @apiBody {String{..255}} name A település neve
     * @apiBody {String{..10}} zip_code Irányítószám
     * @apiBody {Number} county_id Létező megye azonosítója
     *
     * @apiSuccess {Object} city A létrehozott település
     *
     * @apiError (401) Unauthenticated Hiányzó vagy érvénytelen token
     * @apiError (422) ValidationError Hibás mező vagy nem létező megye
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'county_id' => 'required|exists:counties,id',
        ]);

        $city = City::create($request->all());

        return response()->json([
            'city' => $city,
        ]);
    }

    /**
     * @api {put} /api/cities/:id Település módosítása
     * @apiName UpdateCity
     * @apiGroup City
     * @apiHeader {String} Authorization Bearer token
     *
     * @apiParam {Number} id A település azonosítója
     * @apiBody {String} [name] Új név
     * @apiBody {String} [zip_code] Új irányítószám
     * @apiBody {Number} [county_id] Új megye azonosítója
     *
     * @apiSuccess {Object} city A módosított település
     *
     * @apiError (401) Unauthenticated Hiányzó vagy érvénytelen token
     * @apiError (404) NotFound Nincs ilyen település
     * @apiError (422) ValidationError Hibás mező
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'zip_code' => 'sometimes|required|string|max:10',
            'county_id' => 'sometimes|required|exists:counties,id',
        ]);

        $city = City::findOrFail($id);
        $city->update($request->all());

        return response()->json([
            'city' => $city,
        ]);
    }

    /**
     * @api {delete} /api/cities/:id Település törlése
     * @apiName DeleteCity
     * @apiGroup City
     * @apiHeader {String} Authorization Bearer token
     *
     * @apiParam {Number} id A település azonosítója
     *
     * @apiSuccess {String} message "City deleted"
     *
     * @apiError (401) Unauthenticated Hiányzó vagy érvénytelen token
     * @apiError (404) NotFound Nincs ilyen település
     */
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return response()->json([
            'message' => 'City deleted',
        ]);
    }
}