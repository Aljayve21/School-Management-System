<?php

abstract class Middleware {
    abstract public function handle(Request $request, callable $next): void;
}