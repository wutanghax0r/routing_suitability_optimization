INTRODUCTION
------------

Author: Benino Sager

This CLI script enables a user to pick the optimal daily assignments of truck drivers
to destinations for that highest Suitability Score (SS) possible.

It uses an implementation of Heap's algorithm to check every possible
permutation of driver to address.  For each of those it sums up the SS of
all of the assignments.

The script outputs the optimal driver to address assignments on the command line.


ASSUMPTIONS
-----------
Because of ambiguity in the instructions I had to make the following assumptions.
In each case, I would have asked the project manager, client or ss algorithm creators 
for specifics.

Input -
    Address: I assumed that an address would be formatted in the standard multi line format
    similar to what is written on an envelope.  The assumption about the first line
    is that it will contain a street # followed by a space, followed by the street name.
    Optionally after this it could be followed by a comma (,) and then a unit #.   Because of the
    multiline address format the delimiter then must be two newlines instead of one to 
    separate addresses. 
    Address and driver count:  I assumed I had to handle the case of there being more
    addresses than drivers, as well as the case of having more drivers than addresses.

Algorithm -
    Street and address name lengths: I assumed we are only counting the characters excluding
    whitespace.  For example my name, "Benino Sager" would be 11 characters.  I also assumed
    that the punctuation would be counted in the length.  'Oak Ln.' would be 6 characters.
    


REQUIREMENTS
------------
Linux system running PHP 7


INPUT FILES
-----------

This script takes the filenames of two text file as input parameters on the command line.

1st parameter:
file name of a text file containing a new-line (\n) separated list of driver's names.
example:
```
Benino Sager
John Smith
```

2nd parameter:
file name of a text file containing a 2x new-line (\n\n) separaged list of addresses.
example:
```
123 Dog Way
San Diego, CA 92103

742 Evergreen Terrace
Vista, CA 92048
```


EXECUTION
---------

Assumptions:
    The files named as input parameters are in a local subdirectory named input.
    file containing addresses is named 'input_addresses'
    file containing driver's names is named 'input_drivers'

example:
```
php assign_drivers.php input/input_addresses input/input_drivers
```

OUTPUT
------
Assuming the input files described above, this is the expected output:

```
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
```
