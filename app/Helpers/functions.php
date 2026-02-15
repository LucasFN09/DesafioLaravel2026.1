<?php
if (!function_exists('formatar_preco')) {
    /**
     * Converte um valor decimal (banco) para o formato Real Brasileiro.
     * Ex: 1250.50 -> R$ 1.250,50
     */
    function formatar_preco($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
}

if (!function_exists('apenas_numeros')) {
    /**
     * Remove qualquer caractere que não seja número.
     * Útil para tratar CPF e CEP antes de salvar no banco.
     */
    function apenas_numeros($valor) {
        return preg_replace('/[^0-9]/', '', $valor);
    }
}

if (!function_exists('formatar_documento')) {
    /**
     * Formata CPF (000.000.000-00) ou CEP (00.000-000).
     * Detecta automaticamente o tipo pelo tamanho.
     */
    function formatar_documento($valor) {
        $apenas_numeros = apenas_numeros($valor);
        
        if (strlen($apenas_numeros) == 11) {
            // CPF
            return substr($apenas_numeros, 0, 3) . '.' . 
                   substr($apenas_numeros, 3, 3) . '.' . 
                   substr($apenas_numeros, 6, 3) . '-' . 
                   substr($apenas_numeros, 9, 2);
        } elseif (strlen($apenas_numeros) == 8) {
            // CEP
            return substr($apenas_numeros, 0, 5) . '-' . 
                   substr($apenas_numeros, 5, 3);
        }
        
        return $valor;
    }
}

if (!function_exists('is_admin')) {
    /**
     * Verifica de forma rápida se o usuário logado é um administrador.
     * Atende ao RF001 e RF006 do escopo.
     */
    function is_admin() {
        return auth()->check() && auth()->user()->admin;
    }
}