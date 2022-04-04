<?php


//Grab the CLI input parameters
$file_addresses = $argv[1];
$file_drivers   = $argv[2];

/*================= START OF FUNCTIONS=================*/

/*********************************************************
/   Count the number of vowels in a string
/
/   Input: (String) in which to count the vowels
/
/   return Output: (Int) # of vowels found in the string
*********************************************************/ 
function count_vowels($strInput) {
    preg_match_all('/[aeiou]/i', $strInput, $arrMatches);
    return count($arrMatches[0]);
}


/*********************************************************
/   Count the number of consonants in a string
/
/   Input: (String) in which to count the consonants
/
/   Return output: (Int) # of consonants found in the String
*********************************************************/ 
function count_consonants($strInput) {
    preg_match_all('/[bcdfghjklmnpqrstvwxyz]/i',$strInput, $arrMatches);
    return count($arrMatches[0]);
}

function is_even($intNumber) {
    if($intNumber % 2 == 0){
        return true;
    }
    return false;
}


/*********************************************************
/   Find all of the factors of an integer (excluding 1)
/
/   Input: (Int) for which to find factors
/
/   Return output: (Int[]) An array containing the factors
*********************************************************/ 
function get_factors($intNumber) {
    $factors = array();
    for($x = 2; $x <= $intNumber; $x++) {
        if ($intNumber % $x == 0){
            $factors[] = $x;    
        }       
    }
    return $factors;
}


/*********************************************************
/   Find the quantity of common factors for 2
/       integers (excluding 1)
/
/   Input: (Int) for which to find common factors
/           (Int) for which to find common factors
/
/   Return output: (Int) The count of common factors.
*********************************************************/ 
function get_common_factors_count($intNumberA, $intNumberB) {
    $arrAFactors = get_factors($intNumberA);
    $arrBFactors = get_factors($intNumberB);
    return count(array_intersect($arrAFactors, $arrBFactors));
}


/*********************************************************
/   Calculate the suitability score (ss) for a particular
/       driver and destination (both input params)
/
/   Input: (String) normalized driver's name
/           (String) normalized street name
/   *normalized = all lowercase, all whitespace removed
/
/   Return output: (Double) The calculated suitability score.
*********************************************************/ 
function calculate_ss($driver, $street) {
    $ss = 0;
    if(is_even(strlen($street))) {
        $ss = count_vowels($driver) * 1.5;
    }else {
        $ss = count_consonants($driver);
    }
    if(get_common_factors_count(strlen($street),strlen($driver))) {
        $ss = $ss * 1.5;
    }
    return $ss; 
}


/*********************************************************
/ Take as input a multiline address string and return 
/ only the street name with spaces and capitals removed.
/
/ Expected Address format (first line only):
/      (house number) (street name)[,appt/unit #]
/ * The format of the subsequent lines of the address 
/   doesn't matter
/
/   Input: (String) Multiline address string.
/
/   Return output: (String) Normalized street name.
*********************************************************/
 function get_street_name_from_address($address) {
    $firstLine = substr($address,0,strpos($address,"\n"));
    //remove anything after and including a comma
    $firstLine = explode(',', $firstLine)[0];
    $streetName = substr($firstLine,strpos($firstLine," ")+1);
    return strtolower(str_replace(' ', '', $streetName));
}


/*********************************************************
/   Swap two elements of an array.  First input param
/       Is the array reference so that the elements can
/       be swapped in place. 
/
/   Input: (Array Reference) An array of any content type.
/          (Int||String) Index of first array element.
/          (Int||String) Index of second array element.
/   *normalized = all lowercase, all whitespace removed
/
/   Output: (Array) The array reference that was passed in
/           is used to modify that array in place.
*********************************************************/ 
function arr_swap(&$array, $index1, $index2){
    $temp = $array[$index1];
    $array[$index1] = $array[$index2];
    $array[$index2] = $temp;
}



/*********************************************************
/   Calculate the maximum Suitability Score (SS) sum for
/   a list of drivers & a list of destinations recursively.
/   A single input array represents both a list of
/   drivers and a list of destinations, where the array
/   indices represent the drivers, and the values 
/   represent the destinations. The result is stored 
/   in an array passid to the function by reference.
/
/   Input: (Array Ref) Array where the permutation 
/               representing the top SS gets stored.
/          (Array[Int]) array representing the drivers
/               and destinations.
/          (Int) The index in the driver/destination array
/               where we will start working.
/          (Array Ref) array which holds the SS for each 
/               driver-street combo.  Needed for calculating
/               the ss sum to determine the maximum.
/          (Int Ref) used to count the permutations checked
/               just for extra info displayed in the output.
/
/   Output: modifications to references $results & $counter
*********************************************************/
function check_all_possible_assignments_for_max_ss_recursive(&$results, $arrPermutations, $index, &$arrSsResults, &$counter) {
    for($x = $index; $x < count($arrPermutations); $x++){
        arr_swap($arrPermutations, $x, $index);
        check_all_possible_assignments_for_max_ss_recursive($results, $arrPermutations, $index+1,$arrSsResults,$counter);
        arr_swap($arrPermutations, $x, $index);
    }
    if($index == (count($arrPermutations) -1)) {
        $score = 0;
        foreach($arrPermutations as $driver => $address) { 
            if($driver < count($arrSsResults) && $address !== false) {
                $score += $arrSsResults[$driver][$address];
            }
        }
        $arrPermutations['score'] = $score;
        $counter++;
        if(!$results || $results['score'] < $arrPermutations['score']){
            $results = $arrPermutations;
        }
    }
}



function sum_ss($arrPermutations, &$arrSsResults) {
    $ss = 0;
    foreach($arrPermutations as $driver => $address) { 
        if($driver < count($arrSsResults) && $address !== false) {
            $ss += $arrSsResults[$driver][$address];
        }
    }
    return $ss;
} 


/*********************************************************
/   Calculate the maximum Suitability Score (SS) sum for
/   a list of drivers and a list of destinations.
/   A single input array represents both a list of
/   drivers and a list of destinations, where the array
/   indices represent the drivers, and the values 
/   represent the destinations. The result is stored 
/   in an array passid to the function by reference.
/
/   Input: (Array Ref) Array where the permutation 
/               representing the top SS gets stored.
/          (Array[Int]) array representing the drivers
/               and destinations.
/          (Int) The index in the driver/destination array
/               where we will start working.
/          (Array Ref) array which holds the SS for each 
/               driver-street combo.  Needed for calculating
/               the ss sum to determine the maximum.
/          (Int Ref) used to count the permutations checked
/               just for extra info displayed in the output.
/
/   Output: modifications to references $results & $counter
*********************************************************/
function check_all_possible_assignments_for_max_ss(&$results, $arrPermutations, $index, &$arrSsResults, &$counter) {
    // store encoding of the stack state. encode the for loop counter 
    //to simulate the recursive call for $index-1
    $arrStack = array();
    for ($i = 0; $i < $index; $i += 1 ) {
        $arrStack[$i] = 0;
    }

    //calculate the suitability score sum for this permutation
    $ss = sum_ss($arrPermutations, $arrSsResults);
    $counter++;
    //store this permutation if it has the highest ss so far
    if(!$results || $results['score'] < $ss){
        $results = array_merge($arrPermutations, array('score' => $ss));
    }
    
    // $i is like our stack pointer
    $i = 0;
    while ($i < $index) {
        if  ($arrStack[$i] < $i) {
            if ($i % 2 == 0) {//$i is even
                arr_swap($arrPermutations,0, $i);
            }else{//$i is odd
                arr_swap($arrPermutations,$arrStack[$i], $i);
            }
            //calculate the suitability score sum for this permutation
            $ss = sum_ss($arrPermutations, $arrSsResults);
            $counter++;
            //store this permutation if it has the highest ss so far
            if(!$results || $results['score'] < $ss){
                $results = array_merge($arrPermutations, array('score' => $ss));
            }   
            $arrStack[$i] += 1;
            // restart the 'base case' by resetting $i
            $i = 0;
        }else{
            //finished this iteration. simulate popping the stack
            $arrStack[$i] = 0;
            $i += 1;
        }
    }
}


/*================= END OF FUNCTIONS=================*/





//Read addresses from input file & create array of street names
$strAddresses = file_get_contents($file_addresses);
$arrAddresses = explode("\n\n", trim($strAddresses));
$arrStreetNames  = array();
foreach($arrAddresses as $address) {
    $streetName = get_street_name_from_address($address);
    $arrStreetNames[] = array(  
                            'address' => $address, 
                            'normalized_street_name' => $streetName
                            );
}

//Read drivers names from input file & create an array of names
$strDrivers = file_get_contents($file_drivers);
$arrDriversRaw = explode("\n", trim($strDrivers));
$arrDrivers = array();
foreach($arrDriversRaw as $driver) {
    $arrDrivers[] = array(
                            'name' => $driver,
                            'normalized_name' => strtolower(str_replace(' ', '', $driver))
                            );
}

//calculate the suitability score for each driver for all destinations
$arrSsResults = array();
foreach($arrDrivers as $driverIndex => $driver) {
    foreach($arrStreetNames as $streetIndex => $street) {
       $ss = calculate_ss(
                $driver['normalized_name'],
                $street['normalized_street_name']
                );
        if($streetIndex == 0) {
            $arrSsResults[$driverIndex] = array();
        }
        $arrSsResults[$driverIndex][$streetIndex] =  $ss;
    }
}


//build permutations array, pad it with blank (false)  entries
//if there are more drivers than destinations. 
$arrPermutations = array();
for($x = 0; $x<count($arrAddresses); $x++) {
    $arrPermutations[] = $x;
}
while(count($arrPermutations) < count($arrDrivers)) {
    $arrPermutations[] = false;
}


$arrPermutationsResults;
$intPermutationCounter = 0;
//This function call would be removed, as well as the function definition below for production code
//just leaving it here for this assignment because it was my first attempt.  The non-recursive
//implementation called just after this is faster. The recursive implementation worked, but
//took 66% longer in my tests.
//check_all_possible_assignments_for_max_ss($arrPermutationsResults, $arrPermutations, count($arrPermutations), $arrSsResults,$intPermutationCounter);
check_all_possible_assignments_for_max_ss_recursive($arrPermutationsResults, $arrPermutations, 0, $arrSsResults,$intPermutationCounter);


//Output Results
print("\n\n");
print("================================================================\n");
print("    The Maximum suitability score has been calculated: '{$arrPermutationsResults['score']}'\n");
print("         analyzed $intPermutationCounter permutations\n");
print("================================================================\n\n");


$arrDayOffDrivers = array();
$arrUnusedDestinations = array();
foreach($arrPermutationsResults as $driverIndex=>$addressIndex) {
    if($driverIndex === 'score') {
        continue;
    }
    if($driverIndex > (count($arrSsResults) - 1)) {
        $arrUnusedDestinations[] = $arrStreetNames[$addressIndex]['address'];
        continue;
    }
    if($addressIndex === false) {
        $arrDayOffDrivers[] = $arrDrivers[$driverIndex]['name'];
        continue;
    }
    $intSs = $arrSsResults[$driverIndex][$addressIndex];
    print("{$arrDrivers[$driverIndex]['name']} (score - $intSs)\n");
    print("---------------------\n");
    print("{$arrStreetNames[$addressIndex]['address']}\n\n");
}

if($arrDayOffDrivers){
    print("\nThe Following Drivers Get The Day Off!  Wooohoooo!\n");
    print("==================================================\n");
    print(implode("\n",$arrDayOffDrivers));
}

if($arrUnusedDestinations){
    print("\nThe Following Destinations Can't Be Handled Today. :( (not enough drivers)\n");
    print("==================================================\n");
    print(implode("\n--------------------------\n",$arrUnusedDestinations));
}

print("\n");


?>  
