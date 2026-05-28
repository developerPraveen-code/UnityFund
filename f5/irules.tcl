when HTTP_REQUEST {
    # Never allow browser-supplied identity headers to reach the backend.
    HTTP::header remove "X-Authenticated-User"
    HTTP::header remove "X-Authenticated-Email"
    HTTP::header remove "X-Authenticated-Name"
    HTTP::header remove "X-Authenticated-Groups"
    HTTP::header remove "X-ID-Token"

    HTTP::header replace "X-Forwarded-Proto" "https"
    HTTP::header replace "X-Forwarded-Host" [HTTP::host]
}

when ACCESS_ACL_ALLOWED {
    # Add trusted headers only after APM has allowed the request.
    HTTP::header replace "X-Authenticated-User" [ACCESS::session data get "session.oauth.client.last.id_token.sub"]
    HTTP::header replace "X-Authenticated-Email" [ACCESS::session data get "session.oauth.client.last.id_token.email"]
    HTTP::header replace "X-Authenticated-Name" [ACCESS::session data get "session.oauth.client.last.id_token.name"]
    HTTP::header replace "X-ID-Token" [ACCESS::session data get "session.oauth.client.last.id_token"]

    set groups [ACCESS::session data get "session.ad.last.attr.memberOf"]
    if {$groups ne ""} {
        HTTP::header replace "X-Authenticated-Groups" $groups
    }
}
