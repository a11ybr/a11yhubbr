import { FormState } from "../types";

type Props = {
  form: FormState;
  onChange: (field: keyof FormState, value: any) => void;
};

export function ArtigoFields({ form, onChange }: Props) {
  return (
    <fieldset className="space-y-4">
      <legend className="block text-lg font-medium">Destaques do Artigo</legend>
      <div className="space-y-2">
        <label className="block text-sm font-medium">
          Nomes das pessoas autoras
          <span className="text-destructive">*</span>

        </label>
        <input
          type="text"
          className="input-base"
          placeholder="Insira os nomes das pessoas autoras do artigo."
          value={form.autoria || ""}
          onChange={(e) => onChange("autoria", e.target.value)}
          required
        />
        <p className="mt-1 text-xs text-muted-foreground">Separe as pessoas por vírgula.</p>

      </div>




      <div className="space-y-4">
        <span className="block text-sm font-medium">
          Tipo de artigo
          <span aria-hidden="true" className="text-destructive">*</span>
        </span>

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-2">
          {[
            { value: "academico", label: "Acadêmico" },
            { value: "ativismo", label: "Ativismo" },
            { value: "estudo-caso", label: "Estudo de caso" },
            { value: "opinativo", label: "Opinativo" },
            { value: "tecnico", label: "Técnico" },
            { value: "outro", label: "Outro" },
          ].map((option) => (
            <div key={option.value} className="space-y-2">
              <label className="pl-2 text-sm flex items-center gap-2 cursor-pointer">
                <input
                  type="radio"
                  name="tipoArtigo"
                  value={option.value}
                  checked={form.tipoArtigo === option.value}
                  onChange={(e) =>
                    onChange("tipoArtigo", e.target.value)
                  }
                  required
                />
                <span>{option.label}</span>
              </label>

              {option.value === "outro" &&
                form.tipoArtigo === "outro" && (
                  <input
                    type="text"
                    className="input-base"
                    placeholder="Descreva o tipo de artigo."
                    value={form.tipoArtigoOutro || ""}
                    onChange={(e) =>
                      onChange("tipoArtigoOutro", e.target.value)
                    }
                    required
                  />
                )}
            </div>
          ))}
        </div>
      </div>
    </fieldset>
  );
}