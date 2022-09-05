<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display delivery intervals list.
     *
     * @return string
     */
    public function getDelivery(Request $request)
    {
        //cities route array
        $cities = ['Moscow', 'Ruza', 'Kaluga'];
        $result = [];

        if (is_numeric($request->date) && in_array($request->city, $cities)){
            $city = $request->city;
            $d = Carbon::createFromTimestamp($request->date);

            // get correct time based on timezone
            $d->addHours($d->timezone->getType());

            // dismiss today's day
            $d->addDay();

            // dismiss next day if it's [MO|WE|FR] && time hours > 16 && [city_1,city_2]
            // or it's [TU|TH|SA] && time hours > 21 && [city_3]
            if (
                ($d->hour > 15) && ($this->getRoute($city,$cities) == 'first') && $this->ifMoWeFr($d->dayOfWeek) ||
                ($d->hour > 21) && ($this->getRoute($city,$cities) == 'second') && $this->ifTuThSa($d->dayOfWeek)
            ){
                $d->addDay();
            }

            for ($i = 0; count($result) < 21; $i++){
                if (!$this->ifHolidays($d->day,$d->month) &&
                    (
                        ($this->getRoute($city,$cities) == 'first') && $this->ifMoWeFr($d->dayOfWeek) ||
                        ($this->getRoute($city,$cities) == 'second') && $this->ifTuThSa($d->dayOfWeek)
                    )
                ) {
                    $result[] = [
                        'date'=> "{$d->day}.{$d->month}.{$d->year} {$d->hour}",
                        'day' => $d->locale('ru')->dayName,
                        'title' => "{$d->day} {$this->getMonthString($d->month,$d->locale('ru')->monthName)}"
                    ] ;

                }
                $d->addDays(1);
            }
            return json_encode($result);
        }

        return json_encode([ 'result' => 'ERROR! Incorrect information provided.']);
    }

    function getMonthString($monthId, $monthName){
        $a_monthNumbers = [2,7];
        return in_array($monthId, $a_monthNumbers)
            ? substr_replace($monthName,'а',-2)
            : substr_replace($monthName,'я',-2);
    }

    function ifMoWeFr($day){
        return ($day == Carbon::MONDAY || $day == Carbon::WEDNESDAY || $day == Carbon::FRIDAY);
    }

    function ifTuThSa($day){
        return ($day == Carbon::TUESDAY || $day == Carbon::THURSDAY || $day == Carbon::SATURDAY);
    }

    // first - City_1 / City_2 | second - City_3
    function getRoute($city, $cities){
        return (($city != $cities[2]) ? 'first' : 'second');
    }

    function ifHolidays($day, $month){
        // ['$day.$month', ...]
        $holidays = ['1.1', '8.3', '9.5'];
        return in_array("{$day}.{$month}", $holidays);
    }
}
