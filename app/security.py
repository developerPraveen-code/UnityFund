from ipaddress import ip_address, ip_network

from flask import current_app, request


def is_request_from_trusted_proxy() -> bool:
    """Return true only when the immediate peer is an allowed F5 address."""
    cidrs = current_app.config["TRUSTED_PROXY_CIDRS"]
    if not cidrs:
        return False

    proxy_fix_orig = request.environ.get("werkzeug.proxy_fix.orig", {})
    peer_ip = proxy_fix_orig.get("REMOTE_ADDR") or request.remote_addr or ""

    try:
        remote_addr = ip_address(peer_ip)
    except ValueError:
        return False

    return any(remote_addr in ip_network(cidr, strict=False) for cidr in cidrs)


def get_header(name: str) -> str:
    return request.headers.get(name, "").strip()
