<?php

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\Country;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // India States (All 28 States + 8 Union Territories)
        $india = Country::where('code', 'IND')->first();
        if ($india) {
            $indiaStates = [
                ['name' => 'Andhra Pradesh', 'code' => 'AP'],
                ['name' => 'Arunachal Pradesh', 'code' => 'AR'],
                ['name' => 'Assam', 'code' => 'AS'],
                ['name' => 'Bihar', 'code' => 'BR'],
                ['name' => 'Chhattisgarh', 'code' => 'CG'],
                ['name' => 'Goa', 'code' => 'GA'],
                ['name' => 'Gujarat', 'code' => 'GJ'],
                ['name' => 'Haryana', 'code' => 'HR'],
                ['name' => 'Himachal Pradesh', 'code' => 'HP'],
                ['name' => 'Jharkhand', 'code' => 'JH'],
                ['name' => 'Karnataka', 'code' => 'KA'],
                ['name' => 'Kerala', 'code' => 'KL'],
                ['name' => 'Madhya Pradesh', 'code' => 'MP'],
                ['name' => 'Maharashtra', 'code' => 'MH'],
                ['name' => 'Manipur', 'code' => 'MN'],
                ['name' => 'Meghalaya', 'code' => 'ML'],
                ['name' => 'Mizoram', 'code' => 'MZ'],
                ['name' => 'Nagaland', 'code' => 'NL'],
                ['name' => 'Odisha', 'code' => 'OD'],
                ['name' => 'Punjab', 'code' => 'PB'],
                ['name' => 'Rajasthan', 'code' => 'RJ'],
                ['name' => 'Sikkim', 'code' => 'SK'],
                ['name' => 'Tamil Nadu', 'code' => 'TN'],
                ['name' => 'Telangana', 'code' => 'TS'],
                ['name' => 'Tripura', 'code' => 'TR'],
                ['name' => 'Uttar Pradesh', 'code' => 'UP'],
                ['name' => 'Uttarakhand', 'code' => 'UK'],
                ['name' => 'West Bengal', 'code' => 'WB'],
                // Union Territories
                ['name' => 'Andaman and Nicobar Islands', 'code' => 'AN'],
                ['name' => 'Chandigarh', 'code' => 'CH'],
                ['name' => 'Dadra and Nagar Haveli and Daman and Diu', 'code' => 'DN'],
                ['name' => 'Delhi', 'code' => 'DL'],
                ['name' => 'Jammu and Kashmir', 'code' => 'JK'],
                ['name' => 'Ladakh', 'code' => 'LA'],
                ['name' => 'Lakshadweep', 'code' => 'LD'],
                ['name' => 'Puducherry', 'code' => 'PY'],
            ];

            foreach ($indiaStates as $state) {
                State::updateOrCreate(
                    ['code' => $state['code'], 'country_id' => $india->id],
                    $state
                );
            }
        }

        // United States States (All 50 States + DC)
        $usa = Country::where('code', 'USA')->first();
        if ($usa) {
            $usaStates = [
                ['name' => 'Alabama', 'code' => 'AL'],
                ['name' => 'Alaska', 'code' => 'AK'],
                ['name' => 'Arizona', 'code' => 'AZ'],
                ['name' => 'Arkansas', 'code' => 'AR'],
                ['name' => 'California', 'code' => 'CA'],
                ['name' => 'Colorado', 'code' => 'CO'],
                ['name' => 'Connecticut', 'code' => 'CT'],
                ['name' => 'Delaware', 'code' => 'DE'],
                ['name' => 'Florida', 'code' => 'FL'],
                ['name' => 'Georgia', 'code' => 'GA'],
                ['name' => 'Hawaii', 'code' => 'HI'],
                ['name' => 'Idaho', 'code' => 'ID'],
                ['name' => 'Illinois', 'code' => 'IL'],
                ['name' => 'Indiana', 'code' => 'IN'],
                ['name' => 'Iowa', 'code' => 'IA'],
                ['name' => 'Kansas', 'code' => 'KS'],
                ['name' => 'Kentucky', 'code' => 'KY'],
                ['name' => 'Louisiana', 'code' => 'LA'],
                ['name' => 'Maine', 'code' => 'ME'],
                ['name' => 'Maryland', 'code' => 'MD'],
                ['name' => 'Massachusetts', 'code' => 'MA'],
                ['name' => 'Michigan', 'code' => 'MI'],
                ['name' => 'Minnesota', 'code' => 'MN'],
                ['name' => 'Mississippi', 'code' => 'MS'],
                ['name' => 'Missouri', 'code' => 'MO'],
                ['name' => 'Montana', 'code' => 'MT'],
                ['name' => 'Nebraska', 'code' => 'NE'],
                ['name' => 'Nevada', 'code' => 'NV'],
                ['name' => 'New Hampshire', 'code' => 'NH'],
                ['name' => 'New Jersey', 'code' => 'NJ'],
                ['name' => 'New Mexico', 'code' => 'NM'],
                ['name' => 'New York', 'code' => 'NY'],
                ['name' => 'North Carolina', 'code' => 'NC'],
                ['name' => 'North Dakota', 'code' => 'ND'],
                ['name' => 'Ohio', 'code' => 'OH'],
                ['name' => 'Oklahoma', 'code' => 'OK'],
                ['name' => 'Oregon', 'code' => 'OR'],
                ['name' => 'Pennsylvania', 'code' => 'PA'],
                ['name' => 'Rhode Island', 'code' => 'RI'],
                ['name' => 'South Carolina', 'code' => 'SC'],
                ['name' => 'South Dakota', 'code' => 'SD'],
                ['name' => 'Tennessee', 'code' => 'TN'],
                ['name' => 'Texas', 'code' => 'TX'],
                ['name' => 'Utah', 'code' => 'UT'],
                ['name' => 'Vermont', 'code' => 'VT'],
                ['name' => 'Virginia', 'code' => 'VA'],
                ['name' => 'Washington', 'code' => 'WA'],
                ['name' => 'West Virginia', 'code' => 'WV'],
                ['name' => 'Wisconsin', 'code' => 'WI'],
                ['name' => 'Wyoming', 'code' => 'WY'],
                ['name' => 'District of Columbia', 'code' => 'DC'],
            ];

            foreach ($usaStates as $state) {
                State::updateOrCreate(
                    ['code' => $state['code'], 'country_id' => $usa->id],
                    $state
                );
            }
        }

        // Canada Provinces and Territories
        $canada = Country::where('code', 'CAN')->first();
        if ($canada) {
            $canadaStates = [
                ['name' => 'Alberta', 'code' => 'AB'],
                ['name' => 'British Columbia', 'code' => 'BC'],
                ['name' => 'Manitoba', 'code' => 'MB'],
                ['name' => 'New Brunswick', 'code' => 'NB'],
                ['name' => 'Newfoundland and Labrador', 'code' => 'NL'],
                ['name' => 'Nova Scotia', 'code' => 'NS'],
                ['name' => 'Ontario', 'code' => 'ON'],
                ['name' => 'Prince Edward Island', 'code' => 'PE'],
                ['name' => 'Quebec', 'code' => 'QC'],
                ['name' => 'Saskatchewan', 'code' => 'SK'],
                ['name' => 'Northwest Territories', 'code' => 'NT'],
                ['name' => 'Nunavut', 'code' => 'NU'],
                ['name' => 'Yukon', 'code' => 'YT'],
            ];

            foreach ($canadaStates as $state) {
                State::updateOrCreate(
                    ['code' => $state['code'], 'country_id' => $canada->id],
                    $state
                );
            }
        }

        // United Kingdom Countries
        $uk = Country::where('code', 'GBR')->first();
        if ($uk) {
            $ukStates = [
                ['name' => 'England', 'code' => 'ENG'],
                ['name' => 'Scotland', 'code' => 'SCT'],
                ['name' => 'Wales', 'code' => 'WLS'],
                ['name' => 'Northern Ireland', 'code' => 'NIR'],
            ];

            foreach ($ukStates as $state) {
                State::updateOrCreate(
                    ['code' => $state['code'], 'country_id' => $uk->id],
                    $state
                );
            }
        }

        // Australia States and Territories
        $australia = Country::where('code', 'AUS')->first();
        if ($australia) {
            $australiaStates = [
                ['name' => 'New South Wales', 'code' => 'NSW'],
                ['name' => 'Victoria', 'code' => 'VIC'],
                ['name' => 'Queensland', 'code' => 'QLD'],
                ['name' => 'Western Australia', 'code' => 'WA'],
                ['name' => 'South Australia', 'code' => 'SA'],
                ['name' => 'Tasmania', 'code' => 'TAS'],
                ['name' => 'Australian Capital Territory', 'code' => 'ACT'],
                ['name' => 'Northern Territory', 'code' => 'NT'],
            ];

            foreach ($australiaStates as $state) {
                State::updateOrCreate(
                    ['code' => $state['code'], 'country_id' => $australia->id],
                    $state
                );
            }
        }

        // Germany States
        $germany = Country::where('code', 'DEU')->first();
        if ($germany) {
            $germanyStates = [
                ['name' => 'Baden-Württemberg', 'code' => 'BW'],
                ['name' => 'Bavaria', 'code' => 'BY'],
                ['name' => 'Berlin', 'code' => 'BE'],
                ['name' => 'Brandenburg', 'code' => 'BB'],
                ['name' => 'Bremen', 'code' => 'HB'],
                ['name' => 'Hamburg', 'code' => 'HH'],
                ['name' => 'Hesse', 'code' => 'HE'],
                ['name' => 'Lower Saxony', 'code' => 'NI'],
                ['name' => 'Mecklenburg-Vorpommern', 'code' => 'MV'],
                ['name' => 'North Rhine-Westphalia', 'code' => 'NW'],
                ['name' => 'Rhineland-Palatinate', 'code' => 'RP'],
                ['name' => 'Saarland', 'code' => 'SL'],
                ['name' => 'Saxony', 'code' => 'SN'],
                ['name' => 'Saxony-Anhalt', 'code' => 'ST'],
                ['name' => 'Schleswig-Holstein', 'code' => 'SH'],
                ['name' => 'Thuringia', 'code' => 'TH'],
            ];

            foreach ($germanyStates as $state) {
                State::updateOrCreate(
                    ['code' => $state['code'], 'country_id' => $germany->id],
                    $state
                );
            }
        }
    }
}
