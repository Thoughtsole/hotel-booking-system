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
        $city = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30];
        $room_size = 2;

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
        $result = DB::table('rooms')
            ->select('rooms.*', 'room_types.size', 'room_types.price', 'room_types.quantity', 'hotels.id as hotel_id', 'hotels.name as hotel_name')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->join('hotels', 'hotels.id', '=', 'rooms.hotel_id')
            ->whereNotExists(function ($query) use ($check_in, $check_out) {
                $query->select('reservations.id')
                    ->from('reservations')
                    ->join('reservation_room', 'reservations.id', '=', 'reservation_room.reservation_id')
                    // ->whereRaw('rooms.id = reservation_room.room_id')
                    ->whereColumn('rooms.id', 'reservation_room.room_id') // beter option then raw because if database change it not cause erro
                    ->where(function ($q) use ($check_in, $check_out) {
                        $q->where('check_out', '>', $check_in);
                        $q->where('check_in', '<', $check_out);
                        $q->where('room_types.quantity', '=', 0);
                    })
                    ->limit(1);
            })
            ->whereExists(function ($q) use ($city) {
                $q->from('hotels')
                    ->select('hotels.id')
                    ->whereColumn('rooms.hotel_id', 'hotels.id')
                    ->whereExists(function ($q) use ($city) {
                        $q->from('cities')
                            ->select('cities.id')
                            ->whereColumn('hotels.city_id', 'cities.id')
                            ->whereIn('id', $city);
                    });
            })
            ->where('room_types.quantity', '>', 0)
            // ->where('room_types.size', $room_size)
            ->orderBy('room_types.price', 'asc')
            ->get();
            dump($result);

        //larael eloquent ORM style query
        $result = Room::with(['roomType', 'hotel', 'reservations'])
            ->whereDoesntHave('reservations', function ($q) use ($check_in, $check_out) {
                $q->where('check_in', '<', $check_out);
                $q->where('check_out', '>', $check_in);
            })
            // ->withWhereHas('hotel', function ($q) use ($city) { //with and then wherehas or withWhereHas result same
            //     $q->withWhereHas('city', function ($q) use ($city) {
            //         $q->where('id', $city);
            //     });
            // })
            ->whereHas('hotel.city', function ($q) use ($city) { //more short as above
                $q->whereIn('id', $city);
            })
            ->orWhereHas('roomType', function ($q) use ($room_size) {
                $q->where('quantity', '>', 0);
                    // ->where('size', $room_size);
            })
            ->get()
            ->sortBy('roomType.price'); //sortByDesc()

        //booking room
        // $room_id = 17;
        // $user_id = 2;
        // DB::transaction(function () use ($room_id, $user_id, $check_in, $check_out) {

        //     $room = Room::findOrFail($room_id);

        //     $reservation = new \App\Models\Reservation;
        //     $reservation->user_id = $user_id;
        //     $reservation->check_in = $check_in;
        //     $reservation->check_out = $check_out;
        //     $reservation->price = $room->roomType->price;
        //     $reservation->save();

        //     $room->reservations()->attach($reservation->id);

        //     \App\Models\RoomType::where('id', $room->room_type_id)
        //         ->where('quantity', '>', 0)
        //         ->decrement('quantity');
        // });

        dump($result->toArray());
    }
}
