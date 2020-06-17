<?php

namespace App\Jobs;

abstract class SynchronizeGoogleResource
{
    
    protected $synchronizable;
    protected $synchronization;

    public function __construct($synchronizable)
    {
        $this->synchronizable = $synchronizable;
        $this->synchronization = $synchronizable->synchronization;
    }
        
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pageToken = null;
    
        // Get the last syncToken from the synchronization model (initially null).
        $syncToken = $this->synchronization->token;
        
        $service = $this->getGoogleService();
    
        do {
        
            // Provide both tokens to the requests.
            $tokens = compact('pageToken', 'syncToken');
            
            try {
                $list = $this->getGoogleRequest($service, $tokens); 
            
            // If we catch a Google_Service_Exception with a 410 status code.
            } catch (\Google_Service_Exception $e) {    
                if ($e->getCode() === 410) {    
            
                    // Remove the synchronization's token.
                    $this->synchronization->update(['token' => null]);  
            
                    // Drop all items (delegate this task to the subclasses).
                    $this->dropAllSyncedItems();    
            
                    // Start again.
                    return $this->handle(); 
                }   
                throw $e;   
            }
    
            foreach ($list->getItems() as $item) {
                $this->syncItem($item);
            }
    
            $pageToken = $list->getNextPageToken();
        } while ($pageToken);
    
        // When we're done, store the next syncToken and update the last_synchronized_at datetime.
        $this->synchronization->update([
            'token' => $list->getNextSyncToken(),
            'last_synchronized_at' => now(),
        ]);
    }

    abstract public function dropAllSyncedItems();
}
