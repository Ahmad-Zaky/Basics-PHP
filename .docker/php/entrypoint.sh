#!/bin/bash

export $(grep -v '^#' .env | xargs -d '\n')

compser install

make migrate

make serve