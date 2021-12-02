# SpeakAp challenge

## Requirements
```
Solution requires composer.
```

## How it works
```
By runing index.php solution performs:
reads all files in input/ folder, 
chooses parsing strategy by file extension,
parse file data to repository imitation,
process all repository data and calculates values to be found,
reads output/ folder,
looks for the first file,
chooses output data format by file extension,
saves the data to file.
```

## Server side build
```
composer update
```


## Limitations
```
1. no Exception handling. just echoing error
2. folder output/ should have *.txt or *.json file
3. no tests were made due to time limit
```
