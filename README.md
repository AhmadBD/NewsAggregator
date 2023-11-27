
## About News Aggregator
News Aggregator is a web application that allows users to view news articles from various sources.
## Author
Ahmad Bwidani
- [Linked In](https://www.linkedin.com/in/ahmad-bwidani-03507413b/)
- [Github](https://github.com/AhmadBD)
- [E-Mail](mailto:ahmad2135@gmail.com)

## register for API keys
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
to run the scheduler, run the following command in a separate terminal:
```
> php artisan schedule:run
```
## Running the tests
To run the tests, run the following command:
```
> php artisan test
```
## using the api
#### The application has the following endpoints:
- /api/news
- /api/categories
- /api/countries

#### The /api/news endpoint takes the following parameters:
- country: the country of the news source (in ISO 3166-1 alpha-2 format e.g. us, gb, fr, etc.)
- category: the category of the news source (e.g. business, entertainment, general, ... the full list is available at /api/categories)
- search: a search query
- pageSize: the number of articles per page (default: 10)
- page: the page number (default: 1)

### Examples
- /api/news?country=us&category=business
- /api/news?country=us&category=business&pageSize=5&page=2
- /api/news?search=bitcoin
- /api/news?search=bitcoin&pageSize=5&page=2

## License
This project is licensed under the GPL-3.0 License - see the [LICENSE](LICENSE) file for details
