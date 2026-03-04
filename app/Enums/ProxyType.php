<?php

namespace App\Enums;

enum ProxyType: string
{
    case Http = 'http';
    case Https = 'https';
    case Socks4 = 'socks4';
    case Socks5 = 'socks5';
    case Residential = 'residential';
    case Mobile = 'mobile';

    public function label(): string
    {
        return match ($this) {
            ProxyType::Http => 'HTTP',
            ProxyType::Https => 'HTTPS',
            ProxyType::Socks4 => 'SOCKS4',
            ProxyType::Socks5 => 'SOCKS5',
            ProxyType::Residential => 'Residential',
            ProxyType::Mobile => 'Mobile',
        };
    }

    public function color(): string
    {
        return match ($this) {
            ProxyType::Http => 'gray',
            ProxyType::Https => 'blue',
            ProxyType::Socks4 => 'purple',
            ProxyType::Socks5 => 'violet',
            ProxyType::Residential => 'green',
            ProxyType::Mobile => 'amber',
        };
    }
}
