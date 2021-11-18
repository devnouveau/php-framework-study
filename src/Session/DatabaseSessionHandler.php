<?php

namespace Eclair\Session;

use Eclair\Database\Adaptor;

/* CREATE TABLE sessions (
    `id` VARCHAR(255) UNIQUE NOT NULL,
    `payload` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
); */

class DatabaseSessionHandler implements \SessionHandlerInterface 
// SessionHandlerInterface : 커스텀 세션 핸들러를 생성하기 위한 최소한의 프로토타입을 정의하고 있음. 
{

    public function open($savePath, $sessionName) // 세션이 시작될 때 실행되는 콜백 (eg. session_start()호출시)
    {
        return true;
    }

    public function close() // session_write_close() 호출시  호출됨
    {
        return true;
    }

    public function read($id) // 세션읽어오기. 없으면 등록하기 // 세션이 시작될 때 실행되는 콜백. open() 이후에 호출됨
    {
        $data = current(Adaptor::getAll('SELECT * FROM sessions WHERE `id` = ?', [ $id ]));

        if ($data) {
            $payload =  $data->payload;
        } else {
            Adaptor::exec('INSERT INTO sessions(`id`) VALUES(?)', [ $id ]);
        }
        return $payload ?? '';
    }

    public function write($id, $payload) // session_write_close() 호출시 호출됨
    {
        return Adaptor::exec('UPDATE sessions SET `payload` = ? WHERE `id` = ?', [ $payload, $id ]);
    }

    public function destroy($id) // executed when   session_destroy() or with session_regenerate_id() with the destroy parameter set to true
    {
        return Adaptor::exec('DELETE FROM sessions WHERE `id` = ?', [ $id ]);
    }

    public function gc($maxlifetime) // 만료세션 제거
    {
        if ($sessions = Adaptor::getAll('SELECT * FROM sessions')) {
            foreach ($sessions as $session) {
                $timestamp = strtotime($session->created_at);
                if (time() - $timestamp > $maxlifetime) {
                    $this->destroy($session->id);
                }
            }
            return true;
        }
        return false;
    }
}
