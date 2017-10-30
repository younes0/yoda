#!/usr/bin/python

import sys
import simplejson as json
import redis
from goose import Goose

def main(argv):
    try:
        r = redis.StrictRedis('localhost', 6379, 0)

        extractor = Goose({'target_language':'fr'})
        article = extractor.extract(raw_html = r.get('scraped'))
        print(json.dumps(article.cleaned_text))

    except Exception:
        print(json.dumps(None))
            
if __name__ == "__main__":
    main(sys.argv[1:])
    