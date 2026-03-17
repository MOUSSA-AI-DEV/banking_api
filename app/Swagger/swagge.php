<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Authentication API",
    version: "1.0.0",
    description: "Documentation de l'API d'authentification avec Laravel Sanctum"
)]

#[OA\Server(
    url: "http://localhost/api",
    description: "Serveur local"
)]

#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]

class AuthApiDocumentation
{

    #[OA\Post(
        path: "/register",
        summary: "Inscription d'un utilisateur",
        tags: ["Authentification"],

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name","email","password"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Jean Dupont"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "jean@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password123")
                ]
            )
        ),

        responses: [
            new OA\Response(
                response: 201,
                description: "Compte créé avec succès",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Account created successfully"),
                        new OA\Property(property: "token", type: "string", example: "1|abc123xyz...")
                    ]
                )
            ),

            new OA\Response(
                response: 422,
                description: "Erreur de validation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The email has already been taken."),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            )
        ]
    )]
    public function register(){}



    #[OA\Post(
        path: "/login",
        summary: "Connexion utilisateur",
        tags: ["Authentification"],

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email","password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "jean@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password123")
                ]
            )
        ),

        responses: [
            new OA\Response(
                response: 200,
                description: "Connexion réussie",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Login successful"),
                        new OA\Property(property: "token", type: "string", example: "1|abc123xyz...")
                    ]
                )
            ),

            new OA\Response(
                response: 401,
                description: "Identifiants invalides",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Invalid credentials")
                    ]
                )
            )
        ]
    )]
    public function login(){}



    #[OA\Post(
        path: "/logout",
        summary: "Déconnexion utilisateur",
        tags: ["Authentification"],
        security: [["sanctum" => []]],

        responses: [
            new OA\Response(
                response: 200,
                description: "Déconnexion réussie",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Logout successful")
                    ]
                )
            ),

            new OA\Response(
                response: 401,
                description: "Utilisateur non authentifié",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                    ]
                )
            )
        ]
    )]
    public function logout(){}

}