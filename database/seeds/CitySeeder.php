<?php

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\State;
use App\Models\Country;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
        public function run()
    {
        // India Cities
        $india = Country::where('code', 'IND')->first();

        // Maharashtra Cities
        $maharashtra = State::where('code', 'MH')->first();
        if ($maharashtra && $india) {
            $maharashtraCities = [
                ['name' => 'Mumbai', 'latitude' => 19.0760, 'longitude' => 72.8777],
                ['name' => 'Pune', 'latitude' => 18.5204, 'longitude' => 73.8567],
                ['name' => 'Nagpur', 'latitude' => 21.1458, 'longitude' => 79.0882],
                ['name' => 'Thane', 'latitude' => 19.2183, 'longitude' => 72.9781],
                ['name' => 'Nashik', 'latitude' => 19.9975, 'longitude' => 73.7898],
                ['name' => 'Aurangabad', 'latitude' => 19.8762, 'longitude' => 75.3433],
                ['name' => 'Solapur', 'latitude' => 17.6599, 'longitude' => 75.9064],
                ['name' => 'Kolhapur', 'latitude' => 16.7050, 'longitude' => 74.2433],
            ];

            foreach ($maharashtraCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $maharashtra->id],
                    array_merge($city, ['country_id' => $india->id])
                );
            }
        }

        // Delhi Cities
        $delhi = State::where('code', 'DL')->first();
        if ($delhi && $india) {
            $delhiCities = [
                ['name' => 'New Delhi', 'latitude' => 28.6139, 'longitude' => 77.2090],
                ['name' => 'Delhi', 'latitude' => 28.7041, 'longitude' => 77.1025],
                ['name' => 'North Delhi', 'latitude' => 28.7041, 'longitude' => 77.1025],
                ['name' => 'South Delhi', 'latitude' => 28.7041, 'longitude' => 77.1025],
                ['name' => 'East Delhi', 'latitude' => 28.7041, 'longitude' => 77.1025],
                ['name' => 'West Delhi', 'latitude' => 28.7041, 'longitude' => 77.1025],
            ];

            foreach ($delhiCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $delhi->id],
                    array_merge($city, ['country_id' => $india->id])
                );
            }
        }

        // Karnataka Cities
        $karnataka = State::where('code', 'KA')->first();
        if ($karnataka && $india) {
            $karnatakaCities = [
                ['name' => 'Bangalore', 'latitude' => 12.9716, 'longitude' => 77.5946],
                ['name' => 'Mysore', 'latitude' => 12.2958, 'longitude' => 76.6394],
                ['name' => 'Hubli', 'latitude' => 15.3647, 'longitude' => 75.1240],
                ['name' => 'Mangalore', 'latitude' => 12.9716, 'longitude' => 74.8636],
                ['name' => 'Belgaum', 'latitude' => 15.8497, 'longitude' => 74.4977],
                ['name' => 'Gulbarga', 'latitude' => 17.3297, 'longitude' => 76.8343],
            ];

            foreach ($karnatakaCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $karnataka->id],
                    array_merge($city, ['country_id' => $india->id])
                );
            }
        }

        // Tamil Nadu Cities
        $tamilNadu = State::where('code', 'TN')->first();
        if ($tamilNadu && $india) {
            $tamilNaduCities = [
                ['name' => 'Chennai', 'latitude' => 13.0827, 'longitude' => 80.2707],
                ['name' => 'Coimbatore', 'latitude' => 11.0168, 'longitude' => 76.9558],
                ['name' => 'Madurai', 'latitude' => 9.9252, 'longitude' => 78.1198],
                ['name' => 'Salem', 'latitude' => 11.6643, 'longitude' => 78.1460],
                ['name' => 'Tiruchirappalli', 'latitude' => 10.7905, 'longitude' => 78.7047],
                ['name' => 'Vellore', 'latitude' => 12.9165, 'longitude' => 79.1325],
            ];

            foreach ($tamilNaduCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $tamilNadu->id],
                    array_merge($city, ['country_id' => $india->id])
                );
            }
        }

        // Gujarat Cities
        $gujarat = State::where('code', 'GJ')->first();
        if ($gujarat && $india) {
            $gujaratCities = [
                ['name' => 'Ahmedabad', 'latitude' => 23.0225, 'longitude' => 72.5714],
                ['name' => 'Surat', 'latitude' => 21.1702, 'longitude' => 72.8311],
                ['name' => 'Vadodara', 'latitude' => 22.3072, 'longitude' => 73.1812],
                ['name' => 'Rajkot', 'latitude' => 22.3039, 'longitude' => 70.8022],
                ['name' => 'Bhavnagar', 'latitude' => 21.7645, 'longitude' => 72.1519],
                ['name' => 'Jamnagar', 'latitude' => 22.4707, 'longitude' => 70.0577],
            ];

            foreach ($gujaratCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $gujarat->id],
                    array_merge($city, ['country_id' => $india->id])
                );
            }
        }

        // United States Cities
        $usa = Country::where('code', 'USA')->first();

        // California Cities
        $california = State::where('code', 'CA')->first();
        if ($california && $usa) {
            $californiaCities = [
                ['name' => 'Los Angeles', 'latitude' => 34.0522, 'longitude' => -118.2437],
                ['name' => 'San Francisco', 'latitude' => 37.7749, 'longitude' => -122.4194],
                ['name' => 'San Diego', 'latitude' => 32.7157, 'longitude' => -117.1611],
                ['name' => 'Sacramento', 'latitude' => 38.5816, 'longitude' => -121.4944],
                ['name' => 'San Jose', 'latitude' => 37.3382, 'longitude' => -121.8863],
                ['name' => 'Fresno', 'latitude' => 36.7378, 'longitude' => -119.7871],
                ['name' => 'Long Beach', 'latitude' => 33.7701, 'longitude' => -118.1937],
                ['name' => 'Oakland', 'latitude' => 37.8044, 'longitude' => -122.2711],
            ];

            foreach ($californiaCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $california->id],
                    array_merge($city, ['country_id' => $usa->id])
                );
            }
        }

        // New York Cities
        $newYork = State::where('code', 'NY')->first();
        if ($newYork && $usa) {
            $newYorkCities = [
                ['name' => 'New York City', 'latitude' => 40.7128, 'longitude' => -74.0060],
                ['name' => 'Buffalo', 'latitude' => 42.8864, 'longitude' => -78.8784],
                ['name' => 'Rochester', 'latitude' => 43.1566, 'longitude' => -77.6088],
                ['name' => 'Yonkers', 'latitude' => 40.9312, 'longitude' => -73.8987],
                ['name' => 'Syracuse', 'latitude' => 43.0481, 'longitude' => -76.1474],
                ['name' => 'Albany', 'latitude' => 42.6526, 'longitude' => -73.7562],
            ];

            foreach ($newYorkCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $newYork->id],
                    array_merge($city, ['country_id' => $usa->id])
                );
            }
        }

        // Texas Cities
        $texas = State::where('code', 'TX')->first();
        if ($texas && $usa) {
            $texasCities = [
                ['name' => 'Houston', 'latitude' => 29.7604, 'longitude' => -95.3698],
                ['name' => 'San Antonio', 'latitude' => 29.4241, 'longitude' => -98.4936],
                ['name' => 'Dallas', 'latitude' => 32.7767, 'longitude' => -96.7970],
                ['name' => 'Austin', 'latitude' => 30.2672, 'longitude' => -97.7431],
                ['name' => 'Fort Worth', 'latitude' => 32.7555, 'longitude' => -97.3308],
                ['name' => 'El Paso', 'latitude' => 31.7619, 'longitude' => -106.4850],
            ];

            foreach ($texasCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $texas->id],
                    array_merge($city, ['country_id' => $usa->id])
                );
            }
        }

        // Florida Cities
        $florida = State::where('code', 'FL')->first();
        if ($florida && $usa) {
            $floridaCities = [
                ['name' => 'Jacksonville', 'latitude' => 30.3322, 'longitude' => -81.6557],
                ['name' => 'Miami', 'latitude' => 25.7617, 'longitude' => -80.1918],
                ['name' => 'Tampa', 'latitude' => 27.9506, 'longitude' => -82.4572],
                ['name' => 'Orlando', 'latitude' => 28.5383, 'longitude' => -81.3792],
                ['name' => 'St. Petersburg', 'latitude' => 27.7731, 'longitude' => -82.6400],
                ['name' => 'Hialeah', 'latitude' => 25.8576, 'longitude' => -80.2781],
            ];

            foreach ($floridaCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $florida->id],
                    array_merge($city, ['country_id' => $usa->id])
                );
            }
        }

        // United Kingdom Cities
        $uk = Country::where('code', 'GBR')->first();

        // England Cities
        $england = State::where('code', 'ENG')->first();
        if ($england && $uk) {
            $englandCities = [
                ['name' => 'London', 'latitude' => 51.5074, 'longitude' => -0.1278],
                ['name' => 'Manchester', 'latitude' => 53.4808, 'longitude' => -2.2426],
                ['name' => 'Birmingham', 'latitude' => 52.4862, 'longitude' => -1.8904],
                ['name' => 'Liverpool', 'latitude' => 53.4084, 'longitude' => -2.9916],
                ['name' => 'Leeds', 'latitude' => 53.8008, 'longitude' => -1.5491],
                ['name' => 'Sheffield', 'latitude' => 53.3811, 'longitude' => -1.4701],
                ['name' => 'Bradford', 'latitude' => 53.7939, 'longitude' => -1.7524],
                ['name' => 'Bristol', 'latitude' => 51.4545, 'longitude' => -2.5879],
            ];

            foreach ($englandCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $england->id],
                    array_merge($city, ['country_id' => $uk->id])
                );
            }
        }

        // Scotland Cities
        $scotland = State::where('code', 'SCT')->first();
        if ($scotland && $uk) {
            $scotlandCities = [
                ['name' => 'Edinburgh', 'latitude' => 55.9533, 'longitude' => -3.1883],
                ['name' => 'Glasgow', 'latitude' => 55.8642, 'longitude' => -4.2518],
                ['name' => 'Aberdeen', 'latitude' => 57.1497, 'longitude' => -2.0943],
                ['name' => 'Dundee', 'latitude' => 56.4620, 'longitude' => -2.9707],
                ['name' => 'Inverness', 'latitude' => 57.4778, 'longitude' => -4.2247],
                ['name' => 'Perth', 'latitude' => 56.3950, 'longitude' => -3.4308],
            ];

            foreach ($scotlandCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $scotland->id],
                    array_merge($city, ['country_id' => $uk->id])
                );
            }
        }

        // Canada Cities
        $canada = Country::where('code', 'CAN')->first();

        // Ontario Cities
        $ontario = State::where('code', 'ON')->first();
        if ($ontario && $canada) {
            $ontarioCities = [
                ['name' => 'Toronto', 'latitude' => 43.6532, 'longitude' => -79.3832],
                ['name' => 'Ottawa', 'latitude' => 45.4215, 'longitude' => -75.6972],
                ['name' => 'Mississauga', 'latitude' => 43.5890, 'longitude' => -79.6441],
                ['name' => 'Brampton', 'latitude' => 43.6831, 'longitude' => -79.7623],
                ['name' => 'Hamilton', 'latitude' => 43.2557, 'longitude' => -79.8711],
                ['name' => 'London', 'latitude' => 42.9849, 'longitude' => -81.2453],
            ];

            foreach ($ontarioCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $ontario->id],
                    array_merge($city, ['country_id' => $canada->id])
                );
            }
        }

        // Quebec Cities
        $quebec = State::where('code', 'QC')->first();
        if ($quebec && $canada) {
            $quebecCities = [
                ['name' => 'Montreal', 'latitude' => 45.5017, 'longitude' => -73.5673],
                ['name' => 'Quebec City', 'latitude' => 46.8139, 'longitude' => -71.2080],
                ['name' => 'Laval', 'latitude' => 45.5697, 'longitude' => -73.7244],
                ['name' => 'Gatineau', 'latitude' => 45.4765, 'longitude' => -75.7013],
                ['name' => 'Longueuil', 'latitude' => 45.5370, 'longitude' => -73.5103],
                ['name' => 'Sherbrooke', 'latitude' => 45.4000, 'longitude' => -71.8990],
            ];

            foreach ($quebecCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $quebec->id],
                    array_merge($city, ['country_id' => $canada->id])
                );
            }
        }

        // Uttar Pradesh Cities
        $uttarPradesh = State::where('code', 'UP')->first();
        if ($uttarPradesh && $india) {
            $uttarPradeshCities = [
                ['name' => 'Lucknow', 'latitude' => 26.8467, 'longitude' => 80.9462],
                ['name' => 'Kanpur', 'latitude' => 26.4499, 'longitude' => 80.3319],
                ['name' => 'Ghaziabad', 'latitude' => 28.6692, 'longitude' => 77.4538],
                ['name' => 'Agra', 'latitude' => 27.1767, 'longitude' => 78.0081],
                ['name' => 'Varanasi', 'latitude' => 25.3176, 'longitude' => 82.9739],
                ['name' => 'Meerut', 'latitude' => 28.9845, 'longitude' => 77.7064],
                ['name' => 'Prayagraj', 'latitude' => 25.4358, 'longitude' => 81.8463],
                ['name' => 'Bareilly', 'latitude' => 28.3670, 'longitude' => 79.4304],
                ['name' => 'Aligarh', 'latitude' => 27.8974, 'longitude' => 78.0880],
                ['name' => 'Moradabad', 'latitude' => 28.8386, 'longitude' => 78.7733],
                ['name' => 'Saharanpur', 'latitude' => 29.9671, 'longitude' => 77.5452],
                ['name' => 'Gorakhpur', 'latitude' => 26.7606, 'longitude' => 83.3732],
                ['name' => 'Noida', 'latitude' => 28.5355, 'longitude' => 77.3910],
                ['name' => 'Firozabad', 'latitude' => 27.1591, 'longitude' => 78.3957],
                ['name' => 'Jhansi', 'latitude' => 25.4484, 'longitude' => 78.5685],
                ['name' => 'Muzaffarnagar', 'latitude' => 29.4727, 'longitude' => 77.7085],
                ['name' => 'Mathura', 'latitude' => 27.4924, 'longitude' => 77.6737],
                ['name' => 'Budaun', 'latitude' => 28.0362, 'longitude' => 79.1267],
                ['name' => 'Rampur', 'latitude' => 28.7983, 'longitude' => 79.0257],
                ['name' => 'Shahjahanpur', 'latitude' => 27.8815, 'longitude' => 79.9091],
            ];
            foreach ($uttarPradeshCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $uttarPradesh->id],
                    array_merge($city, ['country_id' => $india->id])
                );
            }
        }

        // Andhra Pradesh Cities
        $andhraPradesh = State::where('code', 'AP')->first();
        if ($andhraPradesh && $india) {
            $andhraPradeshCities = [
                ['name' => 'Visakhapatnam', 'latitude' => 17.6868, 'longitude' => 83.2185],
                ['name' => 'Vijayawada', 'latitude' => 16.5062, 'longitude' => 80.6480],
                ['name' => 'Guntur', 'latitude' => 16.3067, 'longitude' => 80.4365],
                ['name' => 'Nellore', 'latitude' => 14.4426, 'longitude' => 79.9865],
                ['name' => 'Kurnool', 'latitude' => 15.8281, 'longitude' => 78.0373],
                ['name' => 'Rajahmundry', 'latitude' => 17.0005, 'longitude' => 81.8040],
                ['name' => 'Kakinada', 'latitude' => 16.9891, 'longitude' => 82.2475],
                ['name' => 'Kadapa', 'latitude' => 14.4772, 'longitude' => 78.8231],
                ['name' => 'Tirupati', 'latitude' => 13.6288, 'longitude' => 79.4192],
                ['name' => 'Anantapur', 'latitude' => 14.6819, 'longitude' => 77.6006],
            ];
            foreach ($andhraPradeshCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $andhraPradesh->id],
                    array_merge($city, ['country_id' => $india->id])
                );
            }
        }

        // West Bengal Cities
        $westBengal = State::where('code', 'WB')->first();
        if ($westBengal && $india) {
            $westBengalCities = [
                ['name' => 'Kolkata', 'latitude' => 22.5726, 'longitude' => 88.3639],
                ['name' => 'Howrah', 'latitude' => 22.5958, 'longitude' => 88.2636],
                ['name' => 'Durgapur', 'latitude' => 23.5204, 'longitude' => 87.3119],
                ['name' => 'Asansol', 'latitude' => 23.6739, 'longitude' => 86.9524],
                ['name' => 'Siliguri', 'latitude' => 26.7271, 'longitude' => 88.3953],
                ['name' => 'Maheshtala', 'latitude' => 22.5087, 'longitude' => 88.2123],
                ['name' => 'Bardhaman', 'latitude' => 23.2324, 'longitude' => 87.8615],
                ['name' => 'Kharagpur', 'latitude' => 22.3460, 'longitude' => 87.2319],
                ['name' => 'Shantipur', 'latitude' => 23.2500, 'longitude' => 88.4333],
                ['name' => 'Bhatpara', 'latitude' => 22.8664, 'longitude' => 88.4011],
            ];
            foreach ($westBengalCities as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'state_id' => $westBengal->id],
                    array_merge($city, ['country_id' => $india->id])
                );
            }
        }

        // --- ALL INDIAN STATES & UNION TERRITORIES ---
        $indianStates = [
            'AP' => [
                ['Visakhapatnam', 17.6868, 83.2185], ['Vijayawada', 16.5062, 80.6480], ['Guntur', 16.3067, 80.4365], ['Nellore', 14.4426, 79.9865], ['Kurnool', 15.8281, 78.0373], ['Rajahmundry', 17.0005, 81.8040], ['Kakinada', 16.9891, 82.2475], ['Kadapa', 14.4772, 78.8231], ['Tirupati', 13.6288, 79.4192], ['Anantapur', 14.6819, 77.6006],
            ],
            'AR' => [
                ['Itanagar', 27.0844, 93.6053], ['Naharlagun', 27.1047, 93.6952], ['Pasighat', 28.0667, 95.3333], ['Tawang', 27.5861, 91.8687],
            ],
            'AS' => [
                ['Guwahati', 26.1445, 91.7362], ['Silchar', 24.8333, 92.7789], ['Dibrugarh', 27.4728, 94.9120], ['Jorhat', 26.7500, 94.2167], ['Nagaon', 26.3500, 92.6833],
            ],
            'BR' => [
                ['Patna', 25.5941, 85.1376], ['Gaya', 24.7969, 85.0002], ['Bhagalpur', 25.3476, 86.9824], ['Muzaffarpur', 26.1225, 85.3906], ['Purnia', 25.7771, 87.4753],
            ],
            'CT' => [
                ['Raipur', 21.2514, 81.6296], ['Bhilai', 21.1938, 81.3509], ['Bilaspur', 22.0797, 82.1391], ['Korba', 22.3458, 82.6964],
            ],
            'GA' => [
                ['Panaji', 15.4909, 73.8278], ['Margao', 15.2750, 73.9581], ['Vasco da Gama', 15.3998, 73.8156], ['Mapusa', 15.5916, 73.8087],
            ],
            'GJ' => [
                ['Ahmedabad', 23.0225, 72.5714], ['Surat', 21.1702, 72.8311], ['Vadodara', 22.3072, 73.1812], ['Rajkot', 22.3039, 70.8022], ['Bhavnagar', 21.7645, 72.1519], ['Jamnagar', 22.4707, 70.0577],
            ],
            'HR' => [
                ['Faridabad', 28.4089, 77.3178], ['Gurgaon', 28.4595, 77.0266], ['Panipat', 29.3909, 76.9635], ['Ambala', 30.3782, 76.7767], ['Yamunanagar', 30.1290, 77.2674],
            ],
            'HP' => [
                ['Shimla', 31.1048, 77.1734],
                ['Mandi', 31.7075, 76.9326],
                ['Solan', 30.9087, 77.0979],
                ['Dharamshala', 32.2190, 76.3234],
                ['Bilaspur', 31.3322, 76.7526],
                ['Hamirpur', 31.6847, 76.5255],
                ['Chamba', 32.5569, 76.1258],
                ['Kullu', 31.9579, 77.1095],
                ['Una', 31.4649, 76.2691],
                ['Kangra', 32.1037, 76.2673],
                ['Sirmaur', 30.7363, 77.1674],
                ['Kinnaur', 31.6857, 78.4752],
                ['Lahaul and Spiti', 32.5707, 77.5856],
                ['Joginder Nagar', 31.9876, 76.7896],
                ['Palampur', 32.1104, 76.5363],
                ['Nahan', 30.5592, 77.2945],
                ['Paonta Sahib', 30.4366, 77.6247],
                ['Sundernagar', 31.5356, 76.9055],
                ['Baddi', 30.9578, 76.7914],
                ['Nalagarh', 31.0500, 76.7221],
                ['Rampur', 31.4497, 77.6291],
                ['Keylong', 32.5740, 77.0336],
                ['Reckong Peo', 31.5382, 78.2752],
                ['Karsog', 31.3812, 77.2046],
                ['Tira Sujanpur', 31.8332, 76.5066],
                ['Nurpur', 32.2998, 75.9066],
                ['Rohru', 31.2020, 77.7526],
                ['Theog', 31.1216, 77.3540],
                ['Arki', 31.1517, 76.9661],
                ['Chopal', 30.9592, 77.5852],
                ['Dalhousie', 32.5420, 75.9810],
                ['Manali', 32.2432, 77.1892],
            ],
            'JH' => [
                ['Ranchi', 23.3441, 85.3096], ['Jamshedpur', 22.8046, 86.2029], ['Dhanbad', 23.7957, 86.4304], ['Bokaro', 23.6693, 86.1511],
            ],
            'KA' => [
                ['Bangalore', 12.9716, 77.5946], ['Mysore', 12.2958, 76.6394], ['Hubli', 15.3647, 75.1240], ['Mangalore', 12.9716, 74.8636], ['Belgaum', 15.8497, 74.4977], ['Gulbarga', 17.3297, 76.8343],
            ],
            'KL' => [
                ['Thiruvananthapuram', 8.5241, 76.9366], ['Kochi', 9.9312, 76.2673], ['Kozhikode', 11.2588, 75.7804], ['Kollam', 8.8932, 76.6141], ['Thrissur', 10.5276, 76.2144],
            ],
            'MP' => [
                ['Indore', 22.7196, 75.8577], ['Bhopal', 23.2599, 77.4126], ['Jabalpur', 23.1815, 79.9864], ['Gwalior', 26.2183, 78.1828], ['Ujjain', 23.1793, 75.7849],
            ],
            'MH' => [
                ['Mumbai', 19.0760, 72.8777], ['Pune', 18.5204, 73.8567], ['Nagpur', 21.1458, 79.0882], ['Thane', 19.2183, 72.9781], ['Nashik', 19.9975, 73.7898], ['Aurangabad', 19.8762, 75.3433], ['Solapur', 17.6599, 75.9064], ['Kolhapur', 16.7050, 74.2433],
            ],
            'MN' => [
                ['Imphal', 24.8170, 93.9368],
            ],
            'ML' => [
                ['Shillong', 25.5788, 91.8933],
            ],
            'MZ' => [
                ['Aizawl', 23.7271, 92.7176],
            ],
            'NL' => [
                ['Kohima', 25.6701, 94.1077], ['Dimapur', 25.9063, 93.7259],
            ],
            'OD' => [
                ['Bhubaneswar', 20.2961, 85.8245], ['Cuttack', 20.4625, 85.8828], ['Rourkela', 22.2604, 84.8536], ['Berhampur', 19.3149, 84.7941],
            ],
            'PB' => [
                ['Ludhiana', 30.9000, 75.8573], ['Amritsar', 31.6340, 74.8723], ['Jalandhar', 31.3260, 75.5762], ['Patiala', 30.3398, 76.3869],
            ],
            'RJ' => [
                ['Jaipur', 26.9124, 75.7873], ['Jodhpur', 26.2389, 73.0243], ['Kota', 25.2138, 75.8648], ['Bikaner', 28.0229, 73.3119], ['Ajmer', 26.4499, 74.6399],
            ],
            'SK' => [
                ['Gangtok', 27.3389, 88.6065],
            ],
            'TN' => [
                ['Chennai', 13.0827, 80.2707], ['Coimbatore', 11.0168, 76.9558], ['Madurai', 9.9252, 78.1198], ['Salem', 11.6643, 78.1460], ['Tiruchirappalli', 10.7905, 78.7047], ['Vellore', 12.9165, 79.1325],
            ],
            'TS' => [
                ['Hyderabad', 17.3850, 78.4867], ['Warangal', 17.9784, 79.6006], ['Nizamabad', 18.6725, 78.0941],
            ],
            'TR' => [
                ['Agartala', 23.8315, 91.2868],
            ],
            'UP' => [
                ['Lucknow', 26.8467, 80.9462], ['Kanpur', 26.4499, 80.3319], ['Ghaziabad', 28.6692, 77.4538], ['Agra', 27.1767, 78.0081], ['Varanasi', 25.3176, 82.9739], ['Meerut', 28.9845, 77.7064], ['Prayagraj', 25.4358, 81.8463], ['Bareilly', 28.3670, 79.4304], ['Aligarh', 27.8974, 78.0880], ['Moradabad', 28.8386, 78.7733], ['Saharanpur', 29.9671, 77.5452], ['Gorakhpur', 26.7606, 83.3732], ['Noida', 28.5355, 77.3910], ['Firozabad', 27.1591, 78.3957], ['Jhansi', 25.4484, 78.5685], ['Muzaffarnagar', 29.4727, 77.7085], ['Mathura', 27.4924, 77.6737], ['Budaun', 28.0362, 79.1267], ['Rampur', 28.7983, 79.0257], ['Shahjahanpur', 27.8815, 79.9091],
            ],
            'UK' => [
                ['Dehradun', 30.3165, 78.0322], ['Haridwar', 29.9457, 78.1642], ['Roorkee', 29.8543, 77.8880],
            ],
            'WB' => [
                ['Kolkata', 22.5726, 88.3639], ['Howrah', 22.5958, 88.2636], ['Durgapur', 23.5204, 87.3119], ['Asansol', 23.6739, 86.9524], ['Siliguri', 26.7271, 88.3953], ['Maheshtala', 22.5087, 88.2123], ['Bardhaman', 23.2324, 87.8615], ['Kharagpur', 22.3460, 87.2319], ['Shantipur', 23.2500, 88.4333], ['Bhatpara', 22.8664, 88.4011],
            ],
            // Union Territories
            'DL' => [ ['New Delhi', 28.6139, 77.2090], ['Delhi', 28.7041, 77.1025], ['North Delhi', 28.7041, 77.1025], ['South Delhi', 28.7041, 77.1025], ['East Delhi', 28.7041, 77.1025], ['West Delhi', 28.7041, 77.1025], ],
            'CH' => [ ['Chandigarh', 30.7333, 76.7794], ],
            'AN' => [ ['Port Blair', 11.6234, 92.7265], ],
            'PY' => [ ['Puducherry', 11.9416, 79.8083], ],
            'DN' => [ ['Daman', 20.3974, 72.8328], ['Diu', 20.7141, 70.9900], ],
            'LD' => [ ['Kavaratti', 10.5667, 72.6167], ],
            'LA' => [ ['Leh', 34.1526, 77.5771], ['Kargil', 34.5595, 76.1349], ],
            'JK' => [ ['Srinagar', 34.0837, 74.7973], ['Jammu', 32.7266, 74.8570], ],
        ];
        foreach ($indianStates as $stateCode => $cities) {
            $state = State::where('code', $stateCode)->first();
            if ($state && $india) {
                foreach ($cities as $city) {
                    City::updateOrCreate(
                        ['name' => $city[0], 'state_id' => $state->id],
                        ['latitude' => $city[1], 'longitude' => $city[2], 'country_id' => $india->id]
                    );
                }
            }
        }
    }
}
