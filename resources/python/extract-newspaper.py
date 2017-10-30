#!/usr/bin/python

import sys
import json
import redis
from newspaper import Article

def main(argv):
    try:
        r = redis.StrictRedis('localhost', 6379, 0)

        article = Article(url = '', fetch_images = False, language = 'fr')
        article.set_html(r.get('scraped'))
        article.parse()
        
        print(json.dumps(article.text))

    except Exception:
        print(json.dumps(None))
            
if __name__ == "__main__":
    main(sys.argv[1:])
