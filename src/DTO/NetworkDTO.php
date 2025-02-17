<?php

namespace DTO;

/**
 * Data Transfer Object for Network Information
 */
class NetworkDTO {
    public string $ssid;
    public string $password;

    /**
     * NetworkDTO constructor.
     *
     * @param string $ssid The network SSID.
     * @param string $password The network password.
     */
    public function __construct(string $ssid, string $password) {
        $this->ssid = $ssid;
        $this->password = $password;
    }

    /**
     * Converts the DTO to an associative array.
     *
     * @return array<string, string> The DTO data as an array.
     */
    public function toArray(): array {
        return [
            'SSID' => $this->ssid,
            'Password' => $this->password
        ];
    }
}
