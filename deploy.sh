#!/bin/sh

set -e
set -x

test -f deploy_key && \
  ssh-agent bash -c "ssh-add deploy_key; git pull;git push gandi; ssh 40204@git.sd5.gpaas.net 'deploy www.rollerderbytestomatic.fr'" || git pull

