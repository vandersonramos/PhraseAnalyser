**Phrase analyser**


Simple Symfony web application that will analyse customer input and provide some statistics.


 - Step 1. Customer is asked to insert a string
 - Step 2. Customer submits the data and receives a grid overview with character statistics.

 - column1 - character symbol
 - column2 - how many times character encountered.
 - column3 - sibling character info: character was seen standing before [list of chars], after [list of chars], longest
distance between chars is 10 (in case of 2 or more characters).

Example:

```
String 'football vs soccer' should output:
f : 1 : before: o after: none
o : 3 : before: o,t,c after: o,f,s max-distance: 10 chars
t : 1 : before: b, after: o
```


## Installation details

1. Clone this repository: `git clone https://github.com/vandersonramos/PhraseAnalyser.git`
2. Go to the project folder: `cd analyser`
3. Run composer: `composer install`
4. Run the server command: `symfony server:start` 

