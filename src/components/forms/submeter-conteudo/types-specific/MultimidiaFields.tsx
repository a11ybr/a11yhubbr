import { FormState } from "../types";

type Props = {
  form: FormState;
  onChange: (field: keyof FormState, value: any) => void;
};

export function MultimidiaFields({ form, onChange }: Props) {

  const toggleArray = (field: keyof FormState, value: string) => {
    const current = (form[field] as string[]) || [];

    const updated = current.includes(value)
      ? current.filter((item) => item !== value)
      : [...current, value];

    onChange(field, updated);
  };

  return (
    <fieldset className="space-y-6">
      <legend className="text-lg font-medium">
        Detalhes da Multimídia
      </legend>

      {/* Tema */}
      <div className="space-y-2">
        <label className="block text-sm font-medium">
          Tema principal
          <span className="text-destructive">*</span>
        </label>
        <input
          type="text"
          className="input-base"
          value={form.tema || ""}
          onChange={(e) => onChange("tema", e.target.value)}
          required
        />
      </div>

      {/* Formato */}
      <div className="space-y-3">
        <span className="block text-sm font-medium">
          Formato
          <span className="text-destructive">*</span>
        </span>

        {["entrevista","mesa-redonda","solo","tecnico","storytelling","outro"]
          .map((option) => (
            <label key={option} className="flex items-center gap-2">
              <input
                type="checkbox"
                checked={form.formato?.includes(option) || false}
                onChange={() => toggleArray("formato", option)}
              />
              <span>{option}</span>
            </label>
        ))}

        {form.formato?.includes("outro") && (
          <input
            type="text"
            className="input-base"
            placeholder="Descreva o formato"
            value={form.formatoOutro || ""}
            onChange={(e) => onChange("formatoOutro", e.target.value)}
          />
        )}
      </div>

      {/* Plataformas */}
      <div className="space-y-3">
        <span className="block text-sm font-medium">
          Plataformas
          <span className="text-destructive">*</span>
        </span>

        {["podcast","youtube","spotify","apple","site","outro"]
          .map((option) => (
            <label key={option} className="flex items-center gap-2">
              <input
                type="checkbox"
                checked={form.plataformas?.includes(option) || false}
                onChange={() => toggleArray("plataformas", option)}
              />
              <span>{option}</span>
            </label>
        ))}

        {form.plataformas?.includes("outro") && (
          <input
            type="text"
            className="input-base"
            placeholder="Descreva a plataforma"
            value={form.plataformaOutro || ""}
            onChange={(e) => onChange("plataformaOutro", e.target.value)}
          />
        )}
      </div>

      {/* Frequência */}
      <div className="space-y-2">
        <span className="block text-sm font-medium">
          Frequência
        </span>

        {["semanal","quinzenal","mensal","pontual"].map((option) => (
          <label key={option} className="flex items-center gap-2">
            <input
              type="radio"
              name="frequencia"
              value={option}
              checked={form.frequencia === option}
              onChange={(e) => onChange("frequencia", e.target.value)}
            />
            <span>{option}</span>
          </label>
        ))}
      </div>

      <label className="flex items-center gap-2">
        <input
          type="checkbox"
          checked={form.transcricao || false}
          onChange={(e) => onChange("transcricao", e.target.checked)}
        />
        <span>Possui transcrição</span>
      </label>

    </fieldset>
  );
}