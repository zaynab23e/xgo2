<?php
namespace App\Http\Controllers\Driver;

use App\Events\DriverLocationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DriverBookingController extends Controller
{
    public function CompletedBooking()
    {
        $driver = Auth::guard('driver')->user(); // Use the user guard
        
        $bookings = $driver->bookings()->with([
            'carModel.modelName.type.brand','user','location','car'
        ])
        ->where('status', 'completed')
        ->orderBy('created_at', 'desc')
        ->get();
        if ($bookings->isEmpty()) {
            return response()->json([
                'message' => __('messages.no_bookings'),
                'data' => []
            ], 404);
        }

        // اختيار الحقول المطلوبة فقط
        $data = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'start_date' => $booking->start_date,
                'end_date'   => $booking->end_date,
                'status'   => $booking->status,
                'payment_method'   => $booking->payment_method,
                'final_price'   => $booking->final_price,
                'car_model_id' => optional($booking->carModel)->id,
                'model_name'     => optional(optional($booking->carModel)->modelName)->name,
                'car_model_year' => optional($booking->carModel)->year,
                'car_model_image' => asset(optional($booking->carModel)->image),
                'Ratings' => [
                    'average_rating' => $booking->carModel->avgRating() ? number_format($booking->carModel->avgRating(), 1) : null,
                    'ratings_count' => $booking->carModel->ratings->count(),
                ],
                'brand_name'     => optional(optional(optional($booking->carModel)->modelName)->type->brand)->name,
                'car_plate_number' => optional($booking->car)->plate_number,               
                'car_color' => optional($booking->car)->color,               
                'user_name' => optional($booking->user)->name,
                'user_email' => optional($booking->user)->email,
                'user_phone' => optional($booking->user)->phone,
                'driver_name' => optional($booking->driver)->name,
                'driver_email' => optional($booking->driver)->email,
                'driver_phone' => optional($booking->driver)->phone,
                'location' => optional($booking->location)->location,
                'latitude' => optional($booking->location)->latitude,
                'longitude' => optional($booking->location)->longitude,
            ];
        });

        return response()->json([
            'message' => __('messages.completed_bookings_retrieved'),
            'data' => $data
        ],);
    }
    public function AssignedBooking()
    {
        $driver = Auth::guard('driver')->user(); // Use the user guard

        $bookings = $driver->bookings()->with([
            'carModel.modelName.type.brand','user','location','car'
        ])
        ->where('status', 'driver_assigned')
        ->orderBy('created_at', 'desc')
        ->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'message' => __('messages.no_bookings'),
                'data' => []
            ], 404);
        }

        // اختيار الحقول المطلوبة فقط
        $data = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'start_date' => $booking->start_date,
                'end_date'   => $booking->end_date,
                'status'   => $booking->status,
                'payment_method'   => $booking->payment_method,
                'final_price'   => $booking->final_price,
                'car_model_id' => optional($booking->carModel)->id,
                'model_name'     => optional(optional($booking->carModel)->modelName)->name,
                'car_model_year' => optional($booking->carModel)->year,
                'car_model_image' => asset(optional($booking->carModel)->image),
                'Ratings' => [
                    'average_rating' => $booking->carModel->avgRating() ? number_format($booking->carModel->avgRating(), 1) : null,
                    'ratings_count' => $booking->carModel->ratings->count(),
                ],
                'brand_name'     => optional(optional(optional($booking->carModel)->modelName)->type->brand)->name,
                'car_plate_number' => optional($booking->car)->plate_number,               
                'car_color' => optional($booking->car)->color,               
                'user_name' => optional($booking->user)->name,
                'user_email' => optional($booking->user)->email,
                'user_phone' => optional($booking->user)->phone,
                'driver_name' => optional($booking->driver)->name,
                'driver_email' => optional($booking->driver)->email,
                'driver_phone' => optional($booking->driver)->phone,
                'location' => optional($booking->location)->location,
                'latitude' => optional($booking->location)->latitude,
                'longitude' => optional($booking->location)->longitude,
            ];
        });

        return response()->json([
            'message' => __('messages.assigned_bookings_retrieved'),
            'data' => $data
        ]);
    }
    public function AcceptedBooking()
    {
        $driver = Auth::guard('driver')->user(); // Use the user guard

        $bookings = $driver->bookings()->with([
            'carModel.modelName.type.brand','user','location','car'
        ])
        ->where('status', 'driver_accepted')
        ->orderBy('created_at', 'desc')
        ->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'message' => __('messages.no_bookings'),
                'data' => []
            ], 404);
        }

        // اختيار الحقول المطلوبة فقط
        $data = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'start_date' => $booking->start_date,
                'end_date'   => $booking->end_date,
                'status'   => $booking->status,
                'payment_method'   => $booking->payment_method,
                'final_price'   => $booking->final_price,
                'car_model_id' => optional($booking->carModel)->id,
                'model_name'     => optional(optional($booking->carModel)->modelName)->name,
                'car_model_year' => optional($booking->carModel)->year,
                'car_model_image' => asset(optional($booking->carModel)->image),
                'Ratings' => [
                    'average_rating' => $booking->carModel->avgRating() ? number_format($booking->carModel->avgRating(), 1) : null,
                    'ratings_count' => $booking->carModel->ratings->count(),
                ],
                'brand_name'     => optional(optional(optional($booking->carModel)->modelName)->type->brand)->name,
                'car_plate_number' => optional($booking->car)->plate_number,               
                'car_color' => optional($booking->car)->color,               
                'user_name' => optional($booking->user)->name,
                'user_email' => optional($booking->user)->email,
                'user_phone' => optional($booking->user)->phone,
                'driver_name' => optional($booking->driver)->name,
                'driver_email' => optional($booking->driver)->email,
                'driver_phone' => optional($booking->driver)->phone,
                'location' => optional($booking->location)->location,
                'latitude' => optional($booking->location)->latitude,
                'longitude' => optional($booking->location)->longitude,
            ];
        });

        return response()->json([
            'message' => __('messages.assigned_bookings_retrieved'),
            'data' => $data
        ]);
    }
    public function changeStatus(Request $request, $id)
    {
        $driver = Auth::guard('driver')->user();
        $booking = $driver->bookings()->with(['user','location','carmodel.modelName.type.brand','car','driver'])->find($id);

        if (!$booking) {
        return response()->json(['message' => __('messages.booking_not_found')], 404);
        }
        
        $request->validate([
            'status' => 'required|in:driver_accepted,canceled,completed',
        ]);
        $booking->status = $request->status;
        if (isset($booking->car)) {
            $booking->car->status = 'available';
            $booking->car->save();

        }
        $booking->save();
        
        return response()->json(['message' => __('messages.status_updated'), 'data' => $booking], 200);
    }
    public function bookingDetails($id)
    {
        $driver = Auth::guard('driver')->user();

        $booking = $driver->bookings()->with(['user', 'location', 'carmodel.modelName.type.brand', 'car'])->find($id);
        if (!$booking) {
            return response()->json(['message' => __('messages.booking_not_found')], 404);
        }

        return response()->json([
            'message' => __('messages.booking_details'),
           'data'=> new BookingResource($booking),
         ], 200);
    }     
    public function updateLocation(Request $request)
    {
        $driver = Auth::guard('driver')->user();
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'location' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Save or update location
        $driver->driverLocations()->updateOrCreate(
            ['driver_id' => $driver->id],
            ['location' => $request->location,'latitude' => $request->latitude, 'longitude' => $request->longitude]
        );

        // If driver is on an active trip, broadcast to rider
        $booking = $driver->bookings()->with(['user','location','carmodel.modelName.type.brand','car','driver'])->find($request->booking_id);


        event(new DriverLocationUpdated($booking->user_id, $driver->id, $request->location,$request->latitude,  $request->longitude));

        return response()->json(['message' => 'Location updated']);
    }      
    public function getBestRoute(Request $request)
    {
        $origin = $request->input('origin'); // e.g., "30.033333,31.233334"
        $destination = $request->input('destination'); // e.g., "30.044420,31.235712"

        $apiKey = env('GOOGLE_MAPS_API_KEY');

        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&key=$apiKey";

        $response = Http::get($url); // Laravel HTTP client

        $data = $response->json();

        // Best route is typically the first one
        $route = $data['routes'][0] ?? null;

        return response()->json([
            'status' => $data['status'],
            'summary' => $route['summary'] ?? null,
            'distance' => $route['legs'][0]['distance']['text'] ?? null,
            'duration' => $route['legs'][0]['duration']['text'] ?? null,
            'steps' => $route['legs'][0]['steps'] ?? [],
            'start address' => $route['legs'][0]['start_address'],
            'end address' => $route['legs'][0]['end_address'],    
            'polyline' => $route['overview_polyline']['points'] ?? null,
        ]);
    }   
}   




