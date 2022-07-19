# Products Up
A command-line program to push a local or remote XML file data to a Google Spreadsheet.

## Requirements
- PHP >= 7.1
- symfony >= 4.4

## Required Changes
Replace credentials.json as per your auth setting and set these .env variables.
```
GOOGLE_SHEET_ID= Use to identify google spreadsheet id as string
GOOGLE_SHEET_RANGE= Use for google spreadsheet range as string
```

## Environment Setup
A docker environment for development and tests are separate and make sure you have installed docker on your machine before using docker. 

### CLI Command
```
docker-compose -f .docker/docker-compose.yml up --build
``` 
How CLI command works
```
php bin/console spreadsheet
```

### Test Command
```
docker-compose -f .docker/test.docker-compose.yml up --build
``` 
How test works
```
./vendor/bin/phpunit
```
