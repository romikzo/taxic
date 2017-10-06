<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Person;

class LocationController extends Controller
{
    public function getPersons()
    {
        $persons = Person::
            select(DB::raw('SUBSTRING_INDEX(SUBSTRING_INDEX(fio, " ", 2), " ", -1) as name, count(*) as count')) //сортируем по именам
            ->groupBy('name')
            ->get();

        return response()->json($persons);
    }

    public function getCoordinates(Request $request)
    {
        if(($count = count($request->input('names'))) > 0) {
            $coordinates = DB::select('
                SELECT c.latitude, c.longitude, p.fio, p.id FROM `coordinates` as c INNER JOIN (
                    SELECT * FROM (
                        SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(fio, " ", 2), " ", -1) as name, id, fio
                        FROM `person`
                    ) AS name
                    WHERE name in (' . trim(str_repeat('?,', $count), ',') . ')
                ) as p on p.id = c.person_id', $request->input('names'));
        } else {
            $coordinates = [];
        }


        return response()->json($coordinates);
    }
}
