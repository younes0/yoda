#!/usr/bin/python

import simplejson as json
import os.path
import redis
import pickle
import pprint
from colorama import *
from textblob import TextBlob
from textblob.classifiers import NaiveBayesClassifier
from textblob.classifiers import MaxEntClassifier

def main():
    r = redis.StrictRedis('localhost', 6379, 0)

    # get/build classifier
    filename = '../../storage/app/classifiers/textblob.pkl'

    if os.path.isfile(filename):
        cl = load_object(filename)
    else:
        trainSet = json.loads(r.get('train_set'))
        cl = NaiveBayesClassifier(trainSet)
        # cl = MaxEntClassifier(trainSet)
        save_object(cl, filename)

    # test
    testSet = json.loads(r.get('test_set'))
    correct = 0
    for doc in testSet:
        classedAs = cl.classify(doc[0])
        print(doc[1] + ';' + classedAs)
        if doc[1] == classedAs:
            correct+= 1

    print(Fore.GREEN + "{} correct on {} documents".format(correct, len(testSet)))

def save_object(obj, filename):
    with open(filename, 'wb') as output:
        pickle.dump(obj, output, pickle.HIGHEST_PROTOCOL)

def load_object(filename):
    file = open(filename, 'rb')
    cl = pickle.load(file)
    file.close()
    return cl

if __name__ == "__main__":
    main()
