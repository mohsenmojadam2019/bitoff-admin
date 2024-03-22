#!/bin/sh

set -e

PROJECT_ROOT="$(git rev-parse --show-toplevel)"
HOOKS_DIR="$(git rev-parse --git-path hooks)"

if [ ! -d "$HOOKS_DIR" ]
then
    mkdir -p "$HOOKS_DIR"
fi

for hook in pre-commit commit-msg
do
    ln -sf "$(realpath --relative-to="$HOOKS_DIR" "$hook")" "$HOOKS_DIR/$hook"
done
