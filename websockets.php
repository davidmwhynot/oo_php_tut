<?php

$address = '0.0.0.0';
$port = 12345;

// Create WebSocket.
$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($server, $address, $port);
socket_listen($server);
$client = socket_accept($server);

// Send WebSocket handshake headers.
$request = socket_read($client, 5000);
preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
$key = base64_encode(pack(
	'H*',
	sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
));
$headers = "HTTP/1.1 101 Switching Protocols\r\n";
$headers .= "Upgrade: websocket\r\n";
$headers .= "Connection: Upgrade\r\n";
$headers .= "Sec-WebSocket-Version: 13\r\n";
$headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
socket_write($client, $headers, strlen($headers));

// Send messages into WebSocket in a loop.
while (true) {
	sleep(1);
	echo "sending" . PHP_EOL;
	$content = 'Now: ' . time();
	$response = chr(129) . chr(strlen($content)) . $content;
	socket_write($client, $response);

	$input = socket_read($client, 64, PHP_NORMAL_READ);
	// $input = socket_read($client, 64, PHP_NORMAL_READ);

	// In most cases, error produces an empty string and not FALSE
	if ($input === false || strcmp($input, '') == 0) {
		$code = socket_last_error($client);

		// You MUST clear the error, or it will not change on next read
		socket_clear_error($client);

		if ($code == SOCKET_EAGAIN) {
			// Nothing to read from non-blocking socket, try again later...
			echo "nothing to read" . PHP_EOL;
		} else {
			// Connection most likely closed, especially if $code is '0'
			echo "connection closed... code: $code" . PHP_EOL;
		}
	} else {
		// Deal with the data
		echo "raw input:" . PHP_EOL;
		echo $input;
		echo PHP_EOL . "chr input:" . PHP_EOL;
		echo chr(129) . $input;
		echo PHP_EOL . "input:" . PHP_EOL;
		var_dump($input);
		echo PHP_EOL . "chr(129) . input:" . PHP_EOL;
		var_dump(chr(129) . $input);
		echo PHP_EOL;
	}

}
