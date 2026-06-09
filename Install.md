# Getting Started

> **NB:** This project is intended for direct server deployments only.
> cPanel and shared hosting environments are **not supported**.
> Recommended: a clean Ubuntu 22+ LTS server or a containerised equivalent via **Podman**.

 - ##### Native Installation Docs
## System Requirements 

| Requirement | Version |
|---|---|
| PHP | ^8.3 |
| Composer | ^2.x |
| PostgreSQL | ^15 |
| Nginx | latest stable -Community Is Supported |
| RoadRunner | latest stable |


### Required PHP Extensions and Configuartion

The following extensions must be enabled in `php.ini` the extension use in the project differ from:

Path to php.ini (or copy and paste if you have no other from ours)

| Extension | Purpose |
|---|---|
| `ext-phar` | Package execution and dependency management |
| `ext-ffi` | Fiber-based context storage (PHP concurrency support) |
| `ext-grpc` | gRPC transport for OTLP exporter |
| `ext-mbstring` | String performance (avoids symfony/polyfill-mbstring fallback) |
| `ext-zlib` | Exported telemetry data compression |
| `ext-protobuf` | Significant OTLP serialisation performance improvement |

> **PECL** must be available on the host to install native extensions (`ext-grpc`, `ext-protobuf`).

### PHP Configuration (`php.ini`)

Fiber support for OpenTelemetry (required for non-CLI SAPIs):

```ini
ffi.preload=/path/to/vendor/open-telemetry/context/fiber/zend_observer_fiber.h
opcache.preload=/path/to/vendor/autoload.php
```

OpenTelemetry environment:

```env
OTEL_PHP_FIBERS_ENABLED=true
```

# Deployment

> **NB:** This project is intended for direct server deployments only.
> cPanel and shared hosting environments are **not supported**.
> Recommended: a clean Ubuntu 24.04 LTS server or a containerised equivalent via **Podman**.

## Option A — Bare Metal (Ubuntu 24.04 LTS)

Direct install on a Ubuntu server or VPS. Full control, no container overhead.

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install core dependencies
sudo apt install -y \
  nginx \
  postgresql-15 \
  php8.3-cli php8.3-fpm php8.3-common \
  php8.3-pgsql php8.3-mbstring php8.3-xml \
  php8.3-curl php8.3-zip php8.3-ffi \
  php8.3-bcmath php8.3-intl \
  phar \
  unzip git curl

# Install PECL extensions (grpc, protobuf)
sudo apt install -y php-pear php8.3-dev
sudo pecl install grpc
sudo pecl install protobuf
```

## Option B — Podman Container

Pull and run the project in a rootless Podman container. No Docker, no daemon.

### Pull the Ubuntu Base Image

```bash
podman pull ubuntu:24.04
```

### Run the Container

```bash
podman run -it \
  --name ngo-erp \
  -p 80:80 \
  -p 443:443 \
  -v /your/project/path:/var/www/ngo-erp:Z \    # CHANGE: host project path
  -v /your/data/path:/var/lib/postgresql/data:Z \ # CHANGE: persistent DB volume
  ubuntu:24.04 \
  /bin/bash
```

> The `:Z` flag sets the correct SELinux label for volume mounts — required on systems with SELinux enforcing.

### Inside the Container

Once inside, follow the same steps as **Option A** — the Ubuntu image is identical.

```bash
apt update && apt upgrade -y
# then proceed with the apt install block above
```

### Persisting the Container

```bash
# Stop
podman stop ngo-erp

# Start again
podman start ngo-erp

# Auto-start on boot (systemd)
podman generate systemd --name ngo-erp --files --new
sudo mv container-ngo-erp.service /etc/systemd/system/
sudo systemctl enable --now container-ngo-erp
```