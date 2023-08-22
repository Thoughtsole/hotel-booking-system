<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function getAvailableRoom()
    {
        //get available rooms between checkin or checkout date

        $check_in = '2023-08-01';
        $check_out = '2023-08-28';
        $city = 16;

        // $result = Reservation::where(function($q) use($check_in, $check_out) {
        //     $q->where('check_in', '>', $check_in);
        //     $q->where('check_in', '>=', $check_out);
        // })
        // ->orWhere(function($q) use($check_in, $check_out) {
        //     $q->where('check_out', '<=', $check_in);
        //     $q->where('check_out', '<', $check_out);
        // })
        // ->get();

        //laravel query builder style
        // $result = DB::table('rooms')
        //     ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
        //     ->whereNotExists(function ($query) use ($check_in, $check_out) {
        //         $query->select('reservations.id')
        //             ->from('reservations')
        //             ->join('reservation_room', 'reservations.id', '=', 'reservation_room.reservation_id')
        //             // ->whereRaw('rooms.id = reservation_room.room_id')
        //             ->whereColumn('rooms.id', 'reservation_room.room_id') // beter option then raw because if database change it not cause erro
        //             ->where(function ($q) use ($check_in, $check_out) {
        //                 $q->where('check_out', '>', $check_in);
        //                 $q->where('check_in', '<', $check_out);
        //             })
        //             ->limit(1);
        //     })
        //     ->get();

        //larael eloquent ORM style query
        $result = Room::with('roomType')
            ->whereDoesntHave('reservations', function ($q) use ($check_in, $check_out) {
                $q->where('check_in', '<', $check_out);
                $q->where('check_out', '>', $check_in);
            })
            ->withWhereHas('hotel', function ($q) use ($city) {
                $q->with('city');
                // $q->withWhereHas('cities', function ($q) use ($city) {
                //     $q->where('city_hotel.city_id', $city);
                // });
            })
            ->get();

        dump($result);
    }
}
