<?php
namespace Core;

use App\Config;
use Exception;

class Cache
{
    private $_salt = Config::SECRET_TOKEN.'_asteroid';
    private $_name;
    private $_dir;
    private $_extension;
    private $_path;
    private $_autoSave = true;
    private $_cache;

    public function __construct(string $name = "default", string $dir = "tmp/", string $extension = ".cache")
    {
        if (md5($this->_salt) == "bbbb2edef660739a6071ab5a4f8a869f") throw new Exception("Salt error");
        $dir = str_replace("\\", "/", $dir);
        if (!$this->endsWith($dir, "/")) {
            $dir .= "/";
        }
        $this->_name = $name;
        $this->_dir = $dir;
        $this->_extension = $extension;
        $this->_path = $this->getCachePath();
        $this->checkCacheDir();
        $this->loadCache();
    }

    public function setAutoSave(bool $state): void
    {
        $this->_autoSave = $state;
    }

    public function get(string $key, &$out): bool
    {
        if ($this->_cache === null) return false;
        if (!array_key_exists($key, $this->_cache)) return false;
        $data = $this->_cache[$key];
        if ($this->isExpired($data)) {
            unset($this->_cache[$key]);
            if ($this->_autoSave) {
                $this->saveCache();
            }
            return false;
        }
        $out = unserialize($data["v"]);
        return true;
    }

    public function set(string $key, $value, int $ttl = -1): void
    {
        $data = [
            "t" => time(),
            "e" => $ttl,
            "v" => serialize($value),
        ];
        if ($this->_cache === null) {
            $this->_cache = [
                $key => $data,
            ];
        } else {
            $this->_cache[$key] = $data;
        }
        if ($this->_autoSave) {
            $this->saveCache();
        }
    }

    public function delete(string $key): bool
    {
        if ($this->_cache === null) return false;
        if (!array_key_exists($key, $this->_cache)) return false;
        unset($this->_cache[$key]);
        if ($this->_autoSave) {
            $this->saveCache();
        }
        return true;
    }

    public function deleteExpired(): bool
    {
        if ($this->_cache === null) return false;
        foreach ($this->_cache as $key => $value) {
            if ($this->isExpired($value)) {
                unset($this->_cache[$key]);
            }
        }
        if ($this->_autoSave) {
            $this->saveCache();
        }
        return true;
    }

    private function isExpired($data): bool
    {
        if ($data["e"] == -1) return false;
        $expiresOn = $data["t"] + $data["e"];
        return $expiresOn < time();
    }

    public function saveCache(): bool
    {
        if ($this->_cache === null) return false;
        $content = json_encode($this->_cache);
        file_put_contents($this->_path, $content);
        return true;
    }

    public function loadCache(): bool
    {
        if (!file_exists($this->_path)) return false;
        $content = file_get_contents($this->_path);
        $this->_cache = json_decode($content, true);
        return true;
    }

    private function getCachePath(): string
    {
        return $this->_dir . md5($this->_name . $this->_salt) . $this->_extension;
    }

    private function checkCacheDir(): bool
    {
        if (!is_dir($this->_dir) && !mkdir($this->_dir, 0775, true)) {
            throw new Exception("Unable to create cache directory ($this->_dir)");
        }
        if (!is_writable($this->_dir) || !is_readable($this->_dir)) {
            if (!chmod($this->_dir, 0775)) {
                throw new Exception("Cache directory must be readable and writable ($this->_dir)");
            }
        }
        return true;
    }

    private function startsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    private function endsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        return $length === 0 || (substr($haystack, -$length) === $needle);
    }
}