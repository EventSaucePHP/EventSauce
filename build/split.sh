#!/usr/bin/env bash

set -e
set -x


if [ ! -f "./build/splitsh-lite" ]; then
    bash build/install-split.sh
fi

CURRENT_BRANCH="master"

function split()
{
    SHA1=`./build/splitsh-lite --prefix=$1 --origin=origin/$CURRENT_BRANCH`
    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
}

function remote()
{
    git remote add $1 $2 || true
}

git pull origin $CURRENT_BRANCH

remote CodeGeneration git@github.com:EventSaucePHP/CodeGeneration.git
remote TestUtilities git@github.com:EventSaucePHP/TestUtilities.git

split 'src/CodeGeneration' CodeGeneration
split 'src/TestUtilities' TestUtilities
