#!/bin/bash
#
# Install Mac OS X Local Dependencies for Abhyagiri Website
#

set -e
cd "$(dirname "$0")/../.."

brew install php@7.3 mysql@5.7 nginx composer

brew cask install google-chrome

brew tap homebrew/services
brew services start mysql@5.7

curl -s -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.1/install.sh | bash
source "$HOME/.nvm/nvm.sh" || true
nvm install --latest-npm
