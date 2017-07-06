#!/bin/bash

cd `dirname "$0"`
cd ..
vendor/bin/dep deploy staging > deployer/builds/`date +%Y%m%d-%H%M%S`.txt
