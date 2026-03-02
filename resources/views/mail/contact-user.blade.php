<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px;">
    <div style="background-color: #0066cc; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h2 style="margin: 0;">Mensagem Administrativa</h2>
    </div>
    
    <div style="padding: 20px; background-color: white;">
        <p style="font-size: 16px; color: #333;">Olá <strong>{{ $usuario->nome }}</strong>,</p>
        
        <p style="font-size: 14px; color: #555; line-height: 1.6;">
            Você recebeu uma mensagem de <strong>{{ $admin->nome }}</strong> (Administrador):
        </p>
        
        <div style="background-color: #f0f0f0; padding: 15px; border-left: 4px solid #0066cc; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; color: #333; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $mensagem }}</p>
        </div>
        
        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
        
        <p style="font-size: 12px; color: #999; text-align: center;">
            Este é um e-mail automático. Por favor, não responda a este endereço.
        </p>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 15px; text-align: center; border-radius: 0 0 8px 8px; font-size: 12px; color: #666;">
        <p style="margin: 0;">{{ config('app.name') }} © {{ date('Y') }}</p>
    </div>
</div>
