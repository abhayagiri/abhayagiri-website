#!/bin/bash
#
# Install Canonical Livepatch for Abhayagiri Website
#
# https://github.com/abhayagiri/abhayagiri-website/blob/dev/scripts/forge/recipes/install-canonical-livepatch.sh
#
# Tokens can be obtained at: https://auth.livepatch.canonical.com/
#
# To get the status of the livepatch on a machine, run:
#
#     canonical-livepatch status
#

LIVEPATCH_TOKEN=

set -e

log="$(mktemp /tmp/install-canonical-livepatch-$(date +%Y%m%d-%H%M%S)-XXXXXX.log)"

chmod 644 "$log"

(

    set -x
    snap install canonical-livepatch
    /snap/bin/canonical-livepatch enable 6d78b81bce40444587ec1a49fadfe3bd

) > "$log" 2>&1
