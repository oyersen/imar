<?php

class Master
{
    /**
     * Get All JSON Data
     */
    function get_all_data()
    {
        $json = (array) json_decode(file_get_contents('data.json'));
        $data = [];
        foreach ($json as $row) {
            $data[$row->id] = $row;
        }
        return $data;
    }

    /**
     * Get single JSON Data
     */
    function get_data($id = '')
    {
        if (!empty($id)) {
            $data = $this->get_all_data();
            if (isset($data[$id])) {
                return $data[$id];
            }
        }
        return (object) [];
    }

    /**
     * Insert Data into JSON File
     */
    function insert_to_json()
    {
        $title = addslashes($_POST['title']);
        $content = addslashes($_POST['content']);
        $created_by = addslashes($_POST['created_by']);


        $data = $this->get_all_data();
        $id = date("YmdHis"); // tarih-zaman bazlı id
        $now = date('Y-m-d H:i:s');

        $data[$id] = (object) [
            "id" => $id,
            "title" => $title,
            "content" => $content,
            'created_at' => $now,
            'updated_at' => $now,
            'is_active' => 1,
            'created_by' => $created_by,
            'updated_by' => $created_by
        ];
        $json = json_encode(array_values($data), JSON_PRETTY_PRINT);
        $insert = file_put_contents('data.json', $json);
        if ($insert) {
            $resp['status'] = 'success';
        } else {
            $resp['failed'] = 'failed';
        }
        return $resp;
    }
    /**
     * Update JSON File Data
     */
    function update_json_data()
    {
        // POST verilerini güvenli bir şekilde alalım
        $id = $_POST['id'];
        $title = addslashes($_POST['title']);
        $content = addslashes($_POST['content']);
        $is_active =  1;
        $created_by = addslashes($_POST['created_by']) ? $_POST['created_by'] : 1;
        $updated_by = addslashes($_POST['updated_by']) ? $_POST['updated_by'] : 1;

        // Mevcut verileri al
        $data = $this->get_all_data();

        // Güncellenme zamanını al
        $updated_at = date('Y-m-d H:i:s');

        // Eğer veri mevcutsa güncelle
        if (isset($data[$id])) {
            $data[$id] = (object) [
                "id" => $id,
                "title" => $title,
                "content" => $content,
                "created_at" => $data[$id]->created_at, // Mevcut oluşturulma zamanı korunuyor
                "updated_at" => $updated_at,
                "is_active" => $is_active,
                "created_by" => $data[$id]->created_by,
                "updated_by" => $updated_by
            ];

            // JSON dosyasını güncelle
            $json = json_encode(array_values($data), JSON_PRETTY_PRINT);
            $update = file_put_contents('data.json', $json);

            if ($update) {
                $resp['status'] = 'success';
            } else {
                $resp['status'] = 'failed';
                $resp['error'] = 'JSON dosyasına yazma işlemi başarısız oldu.';
            }
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = 'Belirtilen ID mevcut değil.';
        }

        return $resp;
    }


    /**
     * Delete Data From JSON File
     */

    function delete_data($id = '')
    {
        if (empty($id)) {
            $resp['status'] = 'failed';
            $resp['error'] = 'Given Member ID is empty.';
        } else {
            $data = $this->get_all_data();
            if (isset($data[$id])) {
                unset($data[$id]);
                $json = json_encode(array_values($data), JSON_PRETTY_PRINT);
                $update = file_put_contents('data.json', $json);
                if ($update) {
                    $resp['status'] = 'success';
                } else {
                    $resp['failed'] = 'failed';
                }
            } else {
                $resp['status'] = 'failed';
                $resp['error'] = 'Given Member ID is not existing on the JSON File.';
            }
        }
        return $resp;
    }
}
