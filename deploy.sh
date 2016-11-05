#!/bin/sh

set -e
set -x

test -f deploy_key && \
  ssh-agent bash -c "ssh-add deploy_key; git pull;git push gandi; ssh 7957567@git.dc0.gpaas.net 'deploy www.rollerderbytestomatic.fr'" || git pull

