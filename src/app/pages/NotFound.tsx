import { Link } from "react-router";
import { AlertCircle } from "lucide-react";

export function NotFound() {
  return (
    <div className="flex-1 flex items-center justify-center py-20">
      <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div className="bg-accent text-primary w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
          <AlertCircle className="w-12 h-12" aria-hidden="true" />
        </div>
        <h1 className="text-4xl md:text-5xl mb-4">Página não encontrada</h1>
        <p className="text-lg text-muted-foreground mb-8">
          Desculpe, a página que você está procurando não existe ou foi movida.
        </p>
        <Link
          to="/"
          className="inline-block bg-primary text-primary-foreground px-8 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
        >
          Voltar para home
        </Link>
      </div>
    </div>
  );
}

