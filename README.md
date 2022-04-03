INTRODUCTION
------------

Author: Benino Sager

This CLI script enables a user to pick the optimal daily assignments of truck drivers
to destinations for that highest Suitability Score (SS) possible.

It uses an implementation of Heap's algorithm to check every possible
permutation of driver to address.  For each of those it sums up the SS of
all of the assignments.

The script outputs the optimal driver to address assignments on the command line.


REQUIREMENTS
------------
Linux system running PHP 7


INPUT FILES
-----------

This script takes the filenames of two text file as input parameters on the command line.

1st parameter:
file name of a text file containing a new-line (\n) separated list of driver's names.
example:
'''
Benino Sager
John Smith
'''

2nd parameter:
file name of a text file containing a 2x new-line (\n\n) separaged list of addresses.
example:
'''
123 Dog Way
San Diego, CA 92103

742 Evergreen Terrace
Vista, CA 92048
'''


EXECUTION
---------

Assumptions:
    The files named as input parameters are in the same directory as the script itself.
    file containing addresses is named 'input_addresses'
    file containing driver's names is named 'input_drivers'

example:
php assign_drivers.php input_addresses input_drivers


OUTPUT
------
Assuming the input files described above, this is the expected output:

'''
================================================================
    The Maximum suitability score has been calculated: '12'
         analyzed 2 permutations
================================================================

Benino Sager (score - 7.5)
---------------------
742 Evergreen Terrace
Vista, CA 92048

John Smith (score - 4.5)
---------------------
123 Dog Way
San Diego, CA 92103
'''
