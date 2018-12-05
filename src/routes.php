<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

//get all pegawai
$app->get("/pegawais/", function (Request $request, Response $response){
    $sql = "SELECT * FROM pegawai";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

//get 1 pegawai by id
$app->get("/pegawais/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $sql = "SELECT * FROM pegawai WHERE id=:id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});


//by search
$app->get("/pegawais/search/", function (Request $request, Response $response, $args){
    $keyword = $request->getQueryParam("keyword");
    $sql = "SELECT * FROM pegawai WHERE nama LIKE '%$keyword%' OR telepon LIKE '%$keyword%' OR alamat LIKE '%$keyword%'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

//post (insert)
$app->post("/pegawais/", function (Request $request, Response $response){

    $new_pegawai = $request->getParsedBody();

    $sql = "INSERT INTO pegawais (id, nama, telepon) VALUE (:id, :nama, :telepon)";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":id" => $new_pegawai["id"],
        ":nama" => $new_pegawai["nama"],
        ":telepon" => $new_pegawai["telepon"]
    ];

    if($stmt->execute($data))
       return $response->withJson(["status" => "success", "data" => "1"], 200);
    
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});
