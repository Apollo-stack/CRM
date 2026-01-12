public function render($request, Throwable $exception)
{
    // Se for uma exceção de validação
    if ($exception instanceof \Illuminate\Validation\ValidationException) {
        return back()
            ->withErrors($exception->errors())
            ->withInput()
            ->with('error', 'Verifique os campos e tente novamente.');
    }
    
    // Se for erro 404
    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        return back()->with('error', 'Recurso não encontrado.');
    }
    
    // Outros erros
    if (!app()->isProduction()) {
        return parent::render($request, $exception);
    }
    
    return back()->with('error', 'Ocorreu um erro inesperado. Tente novamente.');
}