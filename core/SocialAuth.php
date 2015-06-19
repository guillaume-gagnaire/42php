<?php

class                       SocialAuth {
    private                 $provider;

    public function         __construct($provider) {
        $this->provider = $provider;
        if (method_exists($this, 'provider'))
            trigger_error('No such provider.');
    }

    public function         auth() {
        $m = $this->provider;
        return $this->$m();
    }

    /**
     * Return example:
     * {
     *  email
     *  provider
     *  provider_id
     *  firstname
     *  lastname
     *  genre
     *  email_verified
     * }
     */
    private function         facebook() {

    }
}

?>