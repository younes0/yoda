# Yoda

## Intro
Yoda is a Laravel application that curates & classifies trending content from Twitter. 

It does the following routine:
1. Fetches tweets from a Tweeter user list every X minutes.
2. Scrapes contents from the links found in those tweets
3. Uses NLP to classify scraped content

## Setup

### App

```shell
git clone git@github.com:younes0/yoda.git
cd yoda
cp ./resources/env/env.local ./env
composer install
npm install
grunt
```

### Database

1. create pgsql database 'yoda' and 'yoda_testing' (for testing purposes)
2. insert `./database/schemas/yoda_schema.sql`
3. then:
```shell
php artisan migrate:install
php artisan migrate
php artisan db:seed # dev only
```

DB seeder will create an admin account: `admin@admin.com:password`

### Nginx

A configuration example is located at `./resources/nginx/yoda.local.conf`.  
This examples assumes that:
- app is located at `/www/yoda`
- nginx server runs on localhost or lan (192.168.0.0/16)
- `nginx_server_ip local.yoda.com` in your browser's (mac/linux/windows) host entries

### Crontab

```sh
crontab -e
'* * * * * php /www/yoda schedule:run >> /dev/null 2>&1'
```

### Testing

```shell
php codecept run
php codecept run integration|functional
```

For tests involving emailing (such as integration\EventsTest), you will need to run Mailcatcher: 
<http://mailcatcher.me/>

#### Python / Scraping / NLP

```shell
# php-tidy
apt-get install php7.0-tidy

# python3 + newspaper
apt-get -y install python3 python3-dev python3.5-dev python3-pip python3-pil libxml2-dev libxslt-dev libjpeg62 libjpeg62-dev libevent-dev
pip3 install newspaper3k textblob numpy simplejson nltk redis
pip3 install --upgrade beautifulsoup4
curl https://raw.githubusercontent.com/codelucas/newspaper/master/download_corpora.py | python3

# python2 + goose
apt-get install python-dev
wget https://bitbucket.org/pypa/setuptools/raw/bootstrap/ez_setup.py -O - | python2.7; rm setuptools-18.5.zip; sudo easy_install-2.7 pip
pip2 install redis simplejson git+git://github.com/robmcdan/python-goose.git
```

#### PHP-Stemmer

<https://github.com/hthetiot/php-stemmer>

```shell
apt-get install php7.0-dev 

sudo apt-get install libstemmer-dev  -y
git clone https://github.com/jbboehr/php-stemmer.git
cd php-stemmer; phpize; ./configure; make; make install;

echo 'extension=stemmer.so' >> /etc/php/7.0/fpm/php.ini
echo 'extension=stemmer.so' >> /etc/php/7.0/cli/php.ini
```

#### Not used

- PHP-SVM
	```shell
	apt-get install php-pear libsvm-dev php7.0-dev
	pecl install -f svm
	echo 'extension=svm.so' >> /etc/php5/cli/php.ini
	```

- GO for MaxEnt
	```shell
	apt-get install golang
	```

## Configuration

## Database

```sql
-- admin
INSERT INTO users VALUES ('1', 'admin@admin.com', '$2a$10$FNoDCMY4URUBqqy02ngGSuw9sc9kSHUAlbNOZlO.nTNrOHz2CmLrW', 'tBablVJjA5769qBLmAehkgm2bfmb2PKqXuEmQTFWS8mBIEFqbfdZIJTe52gd', 't', 'Admin', 'Admin', null, '2015-07-30 14:49:57', '2015-11-02 11:18:50');

-- origins
-- put your twitter account / list id
INSERT INTO origins (type, account_id, list_id, name) VALUES ('list', 222749144, 223530105, 'law-fr');
```

## Tokens

Generate tokens from frontend '/settings'

## NLP Models (from Local)

Configure Testers (Documents) and create NLP models with Test Command
```
php artisan nlp:test is_law-fr
php artisan nlp:test law-fr
cd ./storage/app/classifiers # generated models
```

