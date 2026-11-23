<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    /**
     * @api {get} /api/counties Megyék listázása
     * @apiName GetCounties
     * @apiGroup County
     *
     * @apiQuery {String} [search] Keresés a megye nevében
     *
     * @apiSuccess {Object[]} counties Megyék listája a városaikkal
     * @apiSuccess {Number} counties.id Azonosító
     * @apiSuccess {String} counties.name Név
     * @apiSuccess {Object[]} counties.cities A megye települései
     */
    public function index(Request $request)
    {
        $query = County::with('cities');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json(['counties' => $query->get()]);
    }

    /**
     * @api {post} /api/counties Új megye felvétele
     * @apiName CreateCounty
     * @apiGroup County
     * @apiHeader {String} Authorization Bearer token
     *
     * @apiBody {String{..255}} name A megye neve
     *
     * @apiSuccess {Object} county A létrehozott megye
     * @apiSuccess {Number} county.id Azonosító
     * @apiSuccess {String} county.name Név
     *
     * @apiError (401) Unauthenticated Hiányzó vagy érvénytelen token
     * @apiError (422) ValidationError Hibás mező
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $county = County::create($request->all());

        return response()->json([
            'county' => $county,
        ]);
    }

    /**
     * @api {put} /api/counties/:id Megye módosítása
     * @apiName UpdateCounty
     * @apiGroup County
     * @apiHeader {String} Authorization Bearer token
     *
     * @apiParam {Number} id A megye azonosítója
     * @apiBody {String} name Az új név
     *
     * @apiSuccess {Object} county A módosított megye
     *
     * @apiError (401) Unauthenticated Hiányzó vagy érvénytelen token
     * @apiError (404) NotFound Nincs ilyen megye
     */
    public function update(Request $request, $id)
    {
        $county = County::findOrFail($id);
        $county->update($request->all());

        return response()->json([
            'county' => $county,
        ]);
    }

    /**
     * @api {delete} /api/counties/:id Megye törlése
     * @apiName DeleteCounty
     * @apiGroup County
     * @apiDescription A megye törlésekor a hozzá tartozó települések is törlődnek.
     * @apiHeader {String} Authorization Bearer token
     *
     * @apiParam {Number} id A megye azonosítója
     *
     * @apiSuccess {String} message "County deleted"
     *
     * @apiError (401) Unauthenticated Hiányzó vagy érvénytelen token
     * @apiError (404) NotFound Nincs ilyen megye
     */
    public function destroy($id)
    {
        $county = County::findOrFail($id);
        $county->delete();

        return response()->json([
            'message' => 'County deleted',
        ]);
    }
}