
## About News Aggregator
News Aggregator is a web application that allows users to view news articles from various sources.
## Author
Ahmad Bwidani
## register for an API key
To use the application, you need to register for an API key from the following websites:
- [News API](https://newsapi.org/)
- [The Guardian](https://open-platform.theguardian.com/access/)
- [New York Times](https://developer.nytimes.com/)
## Running the application
run the following commands on your terminal:
```
> composer install
> cp .env.example .env
> php artisan key:generate
```
Edit your .env file and add the API keys you acquired from the above sites.
then run the following commands:
```
> php artisan serve
```
to run the scheduler, run the following command:
```
> php artisan schedule:run
```
## Running the tests
To run the tests, run the following command (don't forget to edit your .env.testing file to add the API keys):
```
> php artisan test
```
