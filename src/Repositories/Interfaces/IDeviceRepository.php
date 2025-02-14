<?php

namespace Interfaces;

interface IDeviceRepository
{
    public function addDevice(string $name, string $ip): int;
}