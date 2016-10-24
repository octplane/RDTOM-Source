#!/bin/sh

set -e
set -x

test -f deploy_key && \
  ssh-agent bash -c 'ssh-add deploy-key; git pull' || git pull

