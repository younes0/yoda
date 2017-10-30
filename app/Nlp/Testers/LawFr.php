<?php

namespace Yoda\Nlp\Testers;

use Yoda\Nlp\Models\Document;

class LawFr extends AbstractTester 
{
    protected function getTrainingDocs()
    {
        return $this->getAllDocs();
        return $this->getAllDocs()->filter(function($item) {
            // return $item->id > (27205 + 5000);
            return !($item->id % 2);
        });
    }
    
    protected function getTestingDocs()
    {
        return $this->getAllDocs();
        return $this->getAllDocs()->filter(function($item) {
            // return $item->id < (27205 + 5000);
            return !!($item->id % 2);
        });
    }

    protected function getAllDocs()
    {
        return Document::whereIn('domain', $this->config->domains)
            // ->limit(10000)
            ->get();
    }

    // correctCatfirst
    protected function getExtra()
    {
        return \DB::connection('nlp')->select("
            SELECT COUNT(id) FROM documents 
            WHERE SUBSTRING(classified_as, 0, strpos(classified_as, '>')) = SUBSTRING(class, 0, strpos(class, '>')) 
            AND SUBSTRING(classified_as, 0, strpos(classified_as, '>')) != ''
            AND SUBSTRING(class, 0, strpos(class, '>')) != ''
            AND (classified_as != class)
        ")[0]->count;
    }
}
